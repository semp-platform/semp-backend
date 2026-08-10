<?php

use App\Http\Controllers\Web\Nomination\EpmNominationController;
use Illuminate\Support\Facades\Route;

Route::prefix('epm')
    ->name('staff.epm.')
    ->middleware(['auth', 'role:EPM Officer'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Nomination & Vetting
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/nominations',
            [EpmNominationController::class, 'index']
        )
            ->middleware('permission:nominations.view')
            ->name('nominations.index');

        Route::get(
            '/nominations/{nomination}',
            [EpmNominationController::class, 'show']
        )
            ->middleware('permission:nominations.view')
            ->name('nominations.show');

        Route::post(
            '/nominations/{nomination}/forward',
            [EpmNominationController::class, 'forward']
        )
            ->middleware('permission:nominations.review')
            ->name('nominations.forward');
    });
