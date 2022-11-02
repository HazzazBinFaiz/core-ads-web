<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Lib\HyipLab;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Form;
use App\Models\Invest;
use App\Models\PromotionTool;
use App\Models\Referral;
use App\Models\SupportTicket;
use App\Models\TimeSetting;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function home(Request $request)
    {
        $justActivated = false;
        if ($request->filled('activate')) {
            if (auth()->user()->deposit_wallet < 10) {
                $notify[] = ['error', 'Insufficient balance'];
                return back()->withNotify($notify);
            }
            $justActivated = DB::transaction(function (){
                return $this->activate();
            });
        }
        $data['pageTitle']     = 'Dashboard';
        $data['justActivated']     = $justActivated;
        $user                  = auth()->user();
        $data['user']          = $user;
        $data['totalInvest']   = Invest::where('user_id', auth()->id())->sum('amount');
        $data['totalWithdraw'] = Withdrawal::where('user_id', $user->id)->whereIn('status', [1])->sum('amount');
        $data['lastWithdraw']  = Withdrawal::where('user_id', $user->id)->whereIn('status', [1])->latest()->first('amount');
        $data['totalDeposit']  = Deposit::where('user_id', $user->id)->where('status', 1)->sum('amount');
        $data['lastDeposit']   = Deposit::where('user_id', $user->id)->where('status', 1)->latest()->first('amount');
        $data['totalTicket']   = SupportTicket::where('user_id', $user->id)->count();
        $data['transactions']  = $data['user']->transactions->sortByDesc('id')->take(8);

        $data['submittedDeposits']  = Deposit::where('status', '!=', 0)->where('user_id', $user->id)->sum('amount');
        $data['successfulDeposits'] = Deposit::successful()->where('user_id', $user->id)->sum('amount');
        $data['requestedDeposits']  = Deposit::where('user_id', $user->id)->sum('amount');
        $data['initiatedDeposits']  = Deposit::initiated()->where('user_id', $user->id)->sum('amount');
        $data['pendingDeposits']    = Deposit::pending()->where('user_id', $user->id)->sum('amount');
        $data['rejectedDeposits']   = Deposit::rejected()->where('user_id', $user->id)->sum('amount');

        $data['submittedWithdrawals']  = Withdrawal::where('status', '!=', 0)->where('user_id', $user->id)->sum('amount');
        $data['successfulWithdrawals'] = Withdrawal::approved()->where('user_id', $user->id)->sum('amount');
        $data['rejectedWithdrawals']   = Withdrawal::rejected()->where('user_id', $user->id)->sum('amount');
        $data['initiatedWithdrawals']  = Withdrawal::initiated()->where('user_id', $user->id)->sum('amount');
        $data['requestedWithdrawals']  = Withdrawal::where('user_id', $user->id)->sum('amount');
        $data['pendingWithdrawals']    = Withdrawal::pending()->where('user_id', $user->id)->sum('amount');

        $data['invests']               = Invest::where('user_id', $user->id)->sum('amount');
        $data['completedInvests']      = Invest::where('user_id', $user->id)->where('status', 0)->sum('amount');
        $data['runningInvests']        = Invest::where('user_id', $user->id)->where('status', 1)->sum('amount');
        $data['interests']             = Transaction::where('remark', 'interest')->where('user_id', $user->id)->sum('amount');
        $data['depositWalletInvests']  = Invest::where('user_id', $user->id)->where('wallet_type', 'deposit_wallet')->where('status', 1)->sum('amount');
        $data['interestWalletInvests'] = Invest::where('user_id', $user->id)->where('wallet_type', 'interest_wallet')->where('status', 1)->sum('amount');

        $data['isHoliday']      = HyipLab::isHoliDay(now()->toDateTimeString(), gs());
        $data['nextWorkingDay'] = now()->toDateString();
        if ($data['isHoliday']) {
            $data['nextWorkingDay'] = HyipLab::nextWorkingDay(24);
            $data['nextWorkingDay'] = Carbon::parse($data['nextWorkingDay'])->toDateString();
        }

        $data['chartData'] = Transaction::where('remark', 'interest')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('user_id', $user->id)
            ->selectRaw("SUM(amount) as amount, DATE_FORMAT(created_at,'%Y-%m-%d') as date")
            ->orderBy('created_at', 'asc')
            ->groupBy('date')
            ->get();

        return view($this->activeTemplate . 'user.dashboard', $data);
    }

    protected function activate()
    {
        $general   = gs();
        if (is_null(auth()->user()->activated_at)) {
            $user = auth()->user();
            $user->deposit_wallet -= 10;
            $user->activated_at = now();
            $user->save();

            $trx                        = getTrx();
            $transaction                = new Transaction();
            $transaction->user_id       = $user->id;
            $transaction->amount        = 10;
            $transaction->post_balance  = $user->deposit_wallet;
            $transaction->charge        = 0;
            $transaction->trx_type      = '-';
            $transaction->details       = 'Activation';
            $transaction->trx           = $trx;
            $transaction->wallet_type   = 'deposit_wallet';
            $transaction->remark        = 'activation';
            $transaction->save();

            if ($general->referral_activation_bonus > 0 && $referrer = $user->referrer) {
                $transaction2                = new Transaction();
                $transaction2->user_id       = $referrer->id;
                $transaction2->amount        = $general->referral_activation_bonus;
                $transaction2->post_balance  = $referrer->interest_wallet;
                $transaction2->charge        = 0;
                $transaction2->trx_type      = '+';
                $transaction2->details       = 'Referral Activation Bonus';
                $transaction2->trx           = getTrx();
                $transaction2->wallet_type   = 'interest_wallet';
                $transaction2->remark        = 'referral_activation';
                $transaction2->save();
                $referrer->interest_wallet += $general->referral_activation_bonus;
                $referrer->save();
            }

            $direction = $user->place_direction;
            $placement = $user->placement;
            while ($placement && in_array($direction, ['left', 'right'])) {
                if ($placement->activated_at) {
                    $placement->{$direction.'_active'} += 1;
                    $placement->save();
                }
                $direction = $placement->place_direction;
                $placement = $placement->placement;
            }
            return true;
        }
        return false;
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits  = auth()->user()->deposits()->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $general   = gs();
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view($this->activeTemplate . 'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts  = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user     = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts  = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions(Request $request)
    {
        $pageTitle = 'Transactions';
        $remarks   = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark', 'wallet_type'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == 2) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == 1) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form      = Form::where('act', 'kyc')->first();
        return view($this->activeTemplate . 'user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user      = auth()->user();
        $pageTitle = 'KYC Data';
        return view($this->activeTemplate . 'user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form           = Form::where('act', 'kyc')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);

        $userData       = $formProcessor->processFormData($request, $formData);
        $user           = auth()->user();
        $user->kyc_data = $userData;
        $user->kv       = 2;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);

    }

    public function attachmentDownload($fileHash)
    {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general   = gs();
        $title     = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype  = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function userData()
    {
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $pageTitle = 'User Data';
        return view($this->activeTemplate . 'user.user_data', compact('pageTitle', 'user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname' => 'required',
            'lastname'  => 'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->address   = [
            'country' => @$user->address->country,
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'city'    => $request->city,
        ];
        $user->reg_step = 1;
        $user->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function referrals(Request $request)
    {
        $pageTitle = 'Referrals';
        $user      = auth()->user();
        $maxLevel  = Referral::max('level');
        $transactions = $user->transactions()->where('remark', 'referral_activation')->orderBy('id', 'desc')->limit(10)->get();
        return view($this->activeTemplate . 'user.referrals', compact('pageTitle', 'user', 'maxLevel', 'transactions'));
    }

    public function tree(Request $request)
    {
        $pageTitle = 'Tree';
        $user = auth()->user();
        if ($request->filled('placement')) {
            $placement = User::where('username', $request->get('placement'))->firstOrFail();
        } else {
            $placement = auth()->user();
        }
        $transactions = $placement->transactions()->where('remark', 'generation_commission')->orderBy('id', 'desc')->limit(10)->get();
        return view($this->activeTemplate . 'user.tree', compact('pageTitle', 'user', 'placement', 'transactions'));
    }

    public function rank(Request $request)
    {
        $pageTitle = 'Rank';
        $user      = auth()->user();
        $transactions = auth()->user()->transactions()->where('remark', 'rank_upgrade_commission')->orderBy('id', 'desc')->limit(10)->get();
        return view($this->activeTemplate . 'user.rank', compact('pageTitle', 'user', 'transactions'));
    }

    public function promotionalBanners()
    {
        $general = gs();
        if (!$general->promotional_tool) {
            abort(404);
        }
        $pageTitle    = 'Promotional Banners';
        $banners      = PromotionTool::orderBy('id', 'desc')->get();
        $emptyMessage = 'No banner found';
        return view($this->activeTemplate . 'user.promo_tools', compact('pageTitle', 'banners', 'emptyMessage'));
    }

    public function transferBalance()
    {
        $general = gs();
        if (!$general->b_transfer) {
            abort(404);
        }
        $pageTitle = 'Balance Transfer';
        $user      = auth()->user();
        return view($this->activeTemplate . 'user.balance_transfer', compact('pageTitle', 'user'));
    }

    public function transferBalanceSubmit(Request $request)
    {
        $general = gs();
        if (!$general->b_transfer) {
            abort(404);
        }
        $request->validate([
            'username' => 'required',
            'amount'   => 'required|numeric|gt:0',
            'wallet'   => 'required|in:deposit_wallet,interest_wallet',
        ]);

        $user = auth()->user();
        if ($user->username == $request->username) {
            $notify[] = ['error', 'You cannot transfer balance to your own account'];
            return back()->withNotify($notify);
        }

        $receiver = User::where('username', $request->username)->first();
        if (!$receiver) {
            $notify[] = ['error', 'Oops! Receiver not found'];
            return back()->withNotify($notify);
        }

        if ($user->ts) {
            $response = verifyG2fa($user, $request->authenticator_code);
            if (!$response) {
                $notify[] = ['error', 'Wrong verification code'];
                return back()->withNotify($notify);
            }
        }

        $general     = gs();
        $charge      = $general->f_charge + ($request->amount * $general->p_charge) / 100;
        $afterCharge = $request->amount + $charge;
        $wallet      = $request->wallet;

        if ($user->$wallet < $afterCharge) {
            $notify[] = ['error', 'You have no sufficient balance to this wallet'];
            return back()->withNotify($notify);
        }

        $user->$wallet -= $afterCharge;
        $user->save();

        $trx1                      = getTrx();
        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = getAmount($afterCharge);
        $transaction->charge       = $charge;
        $transaction->trx_type     = '-';
        $transaction->trx          = $trx1;
        $transaction->wallet_type  = $wallet;
        $transaction->remark       = 'balance_transfer';
        $transaction->details      = 'Balance transfer to ' . $receiver->username;
        $transaction->post_balance = getAmount($user->$wallet);
        $transaction->save();

        $receiver->deposit_wallet += $request->amount;
        $receiver->save();

        $trx2                      = getTrx();
        $transaction               = new Transaction();
        $transaction->user_id      = $receiver->id;
        $transaction->amount       = getAmount($request->amount);
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->trx          = $trx2;
        $transaction->wallet_type  = 'deposit_wallet';
        $transaction->remark       = 'balance_received';
        $transaction->details      = 'Balance received from ' . $user->username;
        $transaction->post_balance = getAmount($user->deposit_wallet);
        $transaction->save();

        notify($user, 'BALANCE_TRANSFER', [
            'amount'        => showAmount($request->amount),
            'charge'        => showAmount($charge),
            'wallet_type'   => keyToTitle($wallet),
            'post_balance'  => showAmount($user->$wallet),
            'user_fullname' => $receiver->fullname,
            'username'      => $receiver->username,
            'trx'           => $trx1,
        ]);

        notify($receiver, 'BALANCE_RECEIVE', [
            'wallet_type'  => 'Deposit wallet',
            'amount'       => showAmount($request->amount),
            'post_balance' => showAmount($receiver->deposit_wallet),
            'sender'       => $user->username,
            'trx'          => $trx2,
        ]);

        $notify[] = ['success', 'Balance transferred successfully'];
        return back()->withNotify($notify);
    }

    public function findUser(Request $request)
    {
        $user    = User::where('username', $request->username)->first();
        $message = null;
        if (!$user) {
            $message = 'User not found';
        }
        if (@$user->username == auth()->user()->username) {
            $message = 'You cannot send money to your own account';
        }
        return response(['message' => $message]);
    }

}
