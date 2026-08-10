<?php

use App\Http\Controllers\Web\Nomination\CommissionerNominationController;
use Illuminate\Support\Facades\Route;

Route::prefix('commissioner')
    ->name('staff.commissioner.')
    ->middleware(['auth', 'role:Approving Officer'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Nomination Decisions
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/nominations',
            [CommissionerNominationController::class, 'index']
        )
            ->middleware('permission:nominations.view')
            ->name('nominations.index');

        Route::get(
            '/nominations/{nomination}',
            [CommissionerNominationController::class, 'show']
        )
            ->middleware('permission:nominations.view')
            ->name('nominations.show');

        Route::post(
            '/nominations/{nomination}/approve',
            [CommissionerNominationController::class, 'approve']
        )
            ->middleware('permission:nominations.approve')
            ->name('nominations.approve');

        Route::post(
            '/nominations/{nomination}/return',
            [CommissionerNominationController::class, 'return']
        )
            ->middleware('permission:nominations.review')
            ->name('nominations.return');
    });
