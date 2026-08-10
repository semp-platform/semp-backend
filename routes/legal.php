<?php

use App\Http\Controllers\Web\Nomination\LegalNominationController;
use Illuminate\Support\Facades\Route;

Route::prefix('legal')
    ->name('staff.legal.')
    ->middleware(['auth', 'role:Legal Officer'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Nomination & Legal Review
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/nominations',
            [LegalNominationController::class, 'index']
        )
            ->middleware('permission:legal.view')
            ->name('nominations.index');

        Route::get(
            '/nominations/{nomination}',
            [LegalNominationController::class, 'show']
        )
            ->middleware('permission:legal.view')
            ->name('nominations.show');

        Route::post(
            '/nominations/{nomination}/forward',
            [LegalNominationController::class, 'forward']
        )
            ->middleware('permission:legal.review')
            ->name('nominations.forward');
    });
