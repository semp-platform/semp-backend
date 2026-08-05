<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use App\Models\Payment\BatchPayment;
use Illuminate\View\View;

class FinanceReportController extends Controller
{
    public function index(): View
    {
        $payments = BatchPayment::with([
            'politicalParty',
            'batch.election',
        ])
        ->where('status', BatchPayment::STATUS_CONFIRMED)
        ->latest()
        ->get();

        return view(
            'finance.reports.index',
            [

                'payments' => $payments,

                'totalRevenue' => $payments->sum('amount'),

                'todayRevenue' => $payments
                    ->where('confirmed_at', '>=', now()->startOfDay())
                    ->sum('amount'),

            ]
        );
    }
}
