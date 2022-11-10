<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invest;
use App\Models\InvestmentCommissionLog;
use App\Models\JoiningRankUpgradeLog;
use App\Models\MatchingCommissionLog;
use App\Models\NotificationLog;
use App\Models\RankUpgradeLog;
use App\Models\Transaction;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function transaction(Request $request)
    {
        $pageTitle    = 'Transaction Logs';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::with('user')->orderBy('id', 'desc');

        if ($request->search) {
            $search       = request()->search;
            $transactions = $transactions->where(function ($q) use ($search) {
                $q->where('trx', 'like', "%$search%")->orWhereHas('user', function ($user) use ($search) {
                    $user->where('username', 'like', "%$search%");
                });
            });
        }

        $transactions = $transactions->filter(['trx_type', 'remark'])->dateFilter()->paginate(getPaginate());
        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function generationTransaction(Request $request)
    {
        $pageTitle    = 'Activation Generation Bonus';
        $transactions = Transaction::with('user')->where(['remark' => 'activation_generation_commission'])->orderBy('id', 'desc');

        if ($request->search) {
            $search   = request()->search;
            $transactions = $transactions->where(function ($q) use ($search) {
                $q->where('trx', 'like', "%$search%")->orWhereHas('user', function ($user) use ($search) {
                    $user->where('username', 'like', "%$search%");
                });
            });
        }

        $transactions = $transactions->filter(['trx_type'])->dateFilter()->paginate(getPaginate());
        return view('admin.reports.generation-transactions', compact('pageTitle', 'transactions'));
    }

    public function loginHistory(Request $request)
    {
        $loginLogs = UserLogin::orderBy('id', 'desc')->with('user');
        $pageTitle = 'User Login History';
        if ($request->search) {
            $search    = $request->search;
            $pageTitle = 'User Login History - ' . $search;
            $loginLogs = $loginLogs->whereHas('user', function ($query) use ($search) {
                $query->where('username', $search);
            });
        }
        $loginLogs = $loginLogs->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip', $ip)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs      = NotificationLog::orderBy('id', 'desc');
        $search    = $request->search;
        if ($search) {
            $logs = $logs->whereHas('user', function ($user) use ($search) {
                $user->where('username', 'like', "%$search%");
            });
        }
        $logs = $logs->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email     = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }

    public function investHistory(Request $request)
    {
        $pageTitle = 'Invest History';
        $invests   = Invest::with('plan', 'user');

        if ($request->search) {
            $invests = $invests->where(function ($invest) use ($request) {
                $invest->whereHas('user', function ($user) use ($request) {
                    $user->where('username', 'LIKE', "%$request->search%");
                })->orWhereHas('plan', function ($plan) use ($request) {
                    $plan->where('name', 'LIKE', "%$request->search%");
                });
            });
        }

        if ($request->type == 'lifetime') {
            $invests = $invests->where('period', -1);
        } elseif ($request->type == 'repeat') {
            $invests = $invests->where('period', '>', 0);
        }

        $invests = $invests->filter(['status'])->dateFilter();

        $allInvest         = clone $invests;
        $totalInvestCount  = $allInvest->count();
        $totalInvestAmount = $allInvest->sum('amount');
        $totalPaid         = $allInvest->sum('paid');
        $shouldPay         = $allInvest->where('period', '!=', -1)->sum('should_pay');

        $invests = $invests->orderBy('id', 'desc')->paginate(getPaginate());

        return view('admin.reports.invest_history', compact('pageTitle', 'invests', 'totalInvestCount', 'totalInvestAmount', 'totalPaid', 'shouldPay'));
    }

    public function rankUpgradeHistory(Request $request)
    {
        $rankUpgradeLog = RankUpgradeLog::orderBy('id', 'desc')->with('user');
        $pageTitle = 'Rank Upgrade History';
        if ($request->search) {
            $search    = $request->search;
            $pageTitle = 'Rank Upgrade History - ' . $search;
            $rankUpgradeLog = $rankUpgradeLog->whereHas('user', function ($query) use ($search) {
                $query->where('username', $search);
            });
        }
        $rankUpgradeLog = $rankUpgradeLog->paginate(getPaginate());
        return view('admin.reports.rank', compact('pageTitle', 'rankUpgradeLog'));
    }

    public function joiningRankUpgradeHistory(Request $request)
    {
        $rankUpgradeLog = JoiningRankUpgradeLog::orderBy('id', 'desc')->with('user');
        $pageTitle = 'Joining Rank Upgrade History';
        if ($request->search) {
            $search    = $request->search;
            $pageTitle = 'Joining Rank Upgrade History - ' . $search;
            $rankUpgradeLog = $rankUpgradeLog->whereHas('user', function ($query) use ($search) {
                $query->where('username', $search);
            });
        }
        $rankUpgradeLog = $rankUpgradeLog->paginate(getPaginate());
        return view('admin.reports.joining-rank', compact('pageTitle', 'rankUpgradeLog'));
    }

    public function matchingCommissionHistory(Request $request)
    {
        $matchingCommissionLogs = MatchingCommissionLog::orderBy('id', 'desc')->with('user');
        $pageTitle = 'Matching Commission History';
        if ($request->search) {
            $search    = $request->search;
            $pageTitle = 'Matching Commission History - ' . $search;
            $matchingCommissionLogs = $matchingCommissionLogs->whereHas('user', function ($query) use ($search) {
                $query->where('username', $search);
            });
        }
        $matchingCommissionLogs = $matchingCommissionLogs->paginate(getPaginate());
        return view('admin.reports.matching-commission', compact('pageTitle', 'matchingCommissionLogs'));
    }

    public function investmentCommissionHistory(Request $request)
    {
        $investmentCommissionLog = InvestmentCommissionLog::orderBy('id', 'desc')->with('user');
        $pageTitle = 'Investment Commission History';
        if ($request->search) {
            $search    = $request->search;
            $pageTitle = 'Investment Commission History - ' . $search;
            $investmentCommissionLog = $investmentCommissionLog->whereHas('user', function ($query) use ($search) {
                $query->where('username', $search);
            });
        }
        $investmentCommissionLog = $investmentCommissionLog->paginate(getPaginate());
        return view('admin.reports.investment-commission', compact('pageTitle', 'investmentCommissionLog'));
    }
}
