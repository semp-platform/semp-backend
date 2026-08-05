<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Finance\FinanceDashboardController;
use App\Http\Controllers\Web\Finance\FinancePaymentController;
use App\Http\Controllers\Web\Finance\FinanceReportController;

/*
|--------------------------------------------------------------------------
| Finance
|--------------------------------------------------------------------------
*/

Route::prefix('finance')
    ->name('finance.')
    ->middleware('role:Finance Officer')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [FinanceDashboardController::class, 'index']
        )
        ->middleware('permission:payments.view')
        ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Batch Payments
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/payments',
            [FinancePaymentController::class, 'index']
        )
        ->middleware('permission:payments.view')
        ->name('payments.index');

        Route::post(
            '/payments/{batchPayment}/confirm',
            [FinancePaymentController::class, 'confirm']
        )
        ->middleware('permission:payments.verify')
        ->name('payments.confirm');

        Route::get(
            '/payments/{batchPayment}/receipt',
            [FinancePaymentController::class, 'receipt']
        )
        ->middleware('permission:payments.view')
        ->name('payments.receipt');

        Route::get(
            '/payments/{batchPayment}/receipt/download',
            [FinancePaymentController::class, 'downloadReceipt']
        )
        ->middleware('permission:payments.view')
        ->name('payments.receipt.download');

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports',
            [FinanceReportController::class, 'index']
        )
        ->middleware('permission:reports.view')
        ->name('reports.index');

    });
