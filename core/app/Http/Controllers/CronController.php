<?php

namespace App\Http\Controllers;

use App\Lib\HyipLab;
use App\Models\GeneralSetting;
use App\Models\Invest;
use App\Models\InvestmentCommissionLog;
use App\Models\MatchingCommissionLog;
use App\Models\RankUpgradeLog;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CronController extends Controller
{
    public function cron()
    {
        $now                = Carbon::now();
        $general            = GeneralSetting::first();
        $general->last_cron = $now;
        $general->save();

        DB::transaction(function (){
            $this->matching();
        });

        DB::transaction(function (){
            $this->rank();
        });

        $day    = strtolower(date('D'));
        $offDay = (array) $general->off_day;
        if (array_key_exists($day, $offDay)) {
            echo "Holiday";
            exit;
        }

        $invests = Invest::where('status', 1)->where('next_time', '<=', $now)->orderBy('last_time')->take(100)->get();
        foreach ($invests as $invest) {
            $now  = $now;
            $next = HyipLab::nextWorkingDay($invest->plan->time);
            $user = $invest->user;

            $invest->return_rec_time += 1;
            $invest->paid += $invest->interest;
            $invest->should_pay -= $invest->period > 0 ? $invest->interest : 0;
            $invest->next_time = $next;
            $invest->last_time = $now;

            // Add Return Amount to user's Interest Balance
            $user->interest_wallet += $invest->interest;
            $user->save();

            $trx = getTrx();

            // Create The Transaction for Interest Back
            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $invest->interest;
            $transaction->charge       = 0;
            $transaction->post_balance = $user->interest_wallet;
            $transaction->trx_type     = '+';
            $transaction->trx          = $trx;
            $transaction->remark       = 'interest';
            $transaction->wallet_type  = 'interest_wallet';
            $transaction->details      = showAmount($invest->interest) . ' ' . $general->cur_text . ' interest from ' . @$invest->plan->name;
            $transaction->save();

            // Give Referral Commission if Enabled
            if ($general->invest_commission == 1) {
                $commissionType = 'invest_return_commission';
                HyipLab::levelCommission($user, $invest->interest, $commissionType, $trx, $general);
            }

            // Complete the investment if user get full amount as plan
            if ($invest->return_rec_time >= $invest->period && $invest->period != -1) {
                $invest->status = 0; // Change Status so he do not get any more return

                // Give the capital back if plan says the same
                if ($invest->capital_status == 1) {
                    $capital = $invest->amount;
                    $user->interest_wallet += $capital;
                    $user->save();

                    $transaction               = new Transaction();
                    $transaction->user_id      = $user->id;
                    $transaction->amount       = $capital;
                    $transaction->charge       = 0;
                    $transaction->post_balance = $user->interest_wallet;
                    $transaction->trx_type     = '+';
                    $transaction->trx          = $trx;
                    $transaction->wallet_type  = 'interest_wallet';
                    $transaction->remark       = 'capital_return';
                    $transaction->details      = showAmount($capital) . ' ' . $general->cur_text . ' capital back from ' . @$invest->plan->name;
                    $transaction->save();
                }
            }

            $invest->save();

            notify($user, 'INTEREST', [
                'trx'          => $invest->trx,
                'amount'       => showAmount($invest->interest),
                'plan_name'    => @$invest->plan->name,
                'post_balance' => showAmount($user->interest_wallet),
            ]);
        }
    }

    protected function rank()
    {
        foreach (\App\Lib\Rank::getRankAmountMap() as $rank => $amount) {
            $users = User::where('left_investment', '>=', $amount)->where('right_investment', '>=', $amount)->where('rank', '!=', $rank)->get();
            foreach ($users as $user) {
                $user->update(['rank' => $rank]);
                RankUpgradeLog::create([
                    'user_id' => $user->id,
                    'rank' => $rank,
                ]);
            }
        }
    }

    protected function matching()
    {
        $lastMatching = \Illuminate\Support\Carbon::make(cache('_last_matching', now()->subDay()->toDateTimeString()));

        if ($lastMatching->lessThanOrEqualTo(now()->subDay())) {
            $totalMatched = 0;
            $users = User::where('matched', '<', DB::raw('left_active'))->where('matched', '<', DB::raw('right_active'))->get();
            $now = now()->toDateTimeString();

            foreach ($users as $user) {
                $matching = min($user->left_active, $user->right_active) - $user->matched;
                if ($matching > 0) {
                    $totalMatched += $matching;
                }
            }

            $wallet = 'interest_wallet';

            $todayActivated = User::whereBetween('activated_at', [$lastMatching, $now])->count();
            if ($todayActivated > 0) {
                $perMatching = ($todayActivated * 10 * .3)/ $totalMatched;
                foreach ($users as $user) {
                    $matching = min($user->left_active, $user->right_active) - $user->matched;
                    if ($matching > 0) {
                        $amount = $matching * $perMatching;
                        $user->$wallet += $amount;
                        $user->save();
                        MatchingCommissionLog::create([
                            'user_id' => $user->id,
                            'amount' => $amount,
                            'quantity' => $matching
                        ]);
                        $trx                        = getTrx();
                        $transaction                = new Transaction();
                        $transaction->user_id       = $user->id;
                        $transaction->amount        = $amount;
                        $transaction->post_balance  = $user->$wallet;
                        $transaction->charge        = 0;
                        $transaction->trx_type      = '+';
                        $transaction->details       = 'Matching bonus, count : '.$matching;
                        $transaction->trx           = $trx;
                        $transaction->wallet_type   = $wallet;
                        $transaction->remark        = 'matching';
                        $transaction->save();
                    }
                }
            }

            $todayInvested = Invest::whereBetween('created_at', [$lastMatching, $now])->sum('amount');
            if ($todayInvested > 0) {
                $perMatching = ($todayInvested * .04)/ $totalMatched;
                foreach ($users as $user) {
                    $matching = min($user->left_active, $user->right_active) - $user->matched;
                    if ($matching > 0) {
                        $amount = $matching * $perMatching;
                        $user->$wallet += $amount;
                        $user->save();
                        InvestmentCommissionLog::create([
                            'user_id' => $user->id,
                            'amount' => $amount,
                            'quantity' => $matching
                        ]);
                        $trx                        = getTrx();
                        $transaction                = new Transaction();
                        $transaction->user_id       = $user->id;
                        $transaction->amount        = $amount;
                        $transaction->post_balance  = $user->$wallet;
                        $transaction->charge        = 0;
                        $transaction->trx_type      = '+';
                        $transaction->details       = 'Investment matching bonus';
                        $transaction->trx           = $trx;
                        $transaction->wallet_type   = $wallet;
                        $transaction->remark        = 'investment_matching';
                        $transaction->save();
                    }
                }
            }

            foreach ($users as $user) {
                $matching = min($user->left_active, $user->right_active) - $user->matched;
                if ($matching > 0) {
                    $user->matched += $matching;
                    $user->save();
                }
            }

            cache()->put('_last_matching', now()->toDateTimeString());
        }
    }
}
