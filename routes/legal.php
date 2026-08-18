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

                    /*
        |--------------------------------------------------------------------------
        | Shared Legal Documents
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/documents',
            [\App\Http\Controllers\Web\Legal\LegalDocumentController::class, 'index']
        )
            ->middleware('permission:legal.view')
            ->name('documents.index');

        Route::post(
            '/documents',
            [\App\Http\Controllers\Web\Legal\LegalDocumentController::class, 'store']
        )
            ->middleware('permission:legal.review')
            ->name('documents.store');

        Route::get(
            '/documents/{legalDocument}/view',
            [\App\Http\Controllers\Web\Legal\LegalDocumentController::class, 'view']
        )
            ->middleware('permission:legal.view')
            ->name('documents.view');

        Route::get(
            '/documents/{legalDocument}/download',
            [\App\Http\Controllers\Web\Legal\LegalDocumentController::class, 'download']
        )
            ->middleware('permission:legal.view')
            ->name('documents.download');
    });
