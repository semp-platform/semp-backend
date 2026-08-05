<?php

namespace App\Http\Controllers\Web\Finance;

use App\Http\Controllers\Controller;
use App\Models\Payment\BatchPayment;
use Illuminate\View\View;

class FinanceDashboardController extends Controller
{
    public function index(): View
    {
        return view(
            'finance.dashboard',
            [

                'totalPayments' => BatchPayment::count(),

                'pendingPayments' => BatchPayment::where(
                    'status',
                    BatchPayment::STATUS_PENDING
                )->count(),

                'paidPayments' => BatchPayment::where(
                    'status',
                    BatchPayment::STATUS_PAID
                )->count(),

                'confirmedPayments' => BatchPayment::whereNotNull(
                    'confirmed_at'
                )->count(),

                'totalRevenue' => BatchPayment::whereIn(
    'status',
    [
        BatchPayment::STATUS_PAID,
        BatchPayment::STATUS_CONFIRMED,
    ]
)->sum('amount'),

            ]
        );
    }
}
