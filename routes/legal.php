<?php

use App\Http\Controllers\Web\Nomination\LegalNominationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Legal\LegalDashboardController;
use App\Http\Controllers\Web\Legal\LegalCandidateController;
use App\Http\Controllers\Web\Legal\LegalPoliticalPartyController;
use App\Http\Controllers\Web\Legal\LegalWorkflowHistoryController;


Route::prefix('legal')
    ->name('staff.legal.')
    ->middleware(['auth', 'role:Legal Officer'])
    ->group(function () {

    /*
|--------------------------------------------------------------------------
| Candidate Records
|--------------------------------------------------------------------------
*/

Route::get(
    '/candidates',
    [LegalCandidateController::class, 'index']
)
    ->middleware('permission:legal.view')
    ->name('candidates.index');

Route::get(
    '/candidates/{candidate}',
    [LegalCandidateController::class, 'show']
)
    ->middleware('permission:legal.view')
    ->name('candidates.show');


/*
|--------------------------------------------------------------------------
| Political Parties
|--------------------------------------------------------------------------
*/

Route::get(
    '/political-parties',
    [LegalPoliticalPartyController::class, 'index']
)
    ->middleware('permission:legal.view')
    ->name('political-parties.index');

Route::get(
    '/political-parties/{politicalParty}',
    [LegalPoliticalPartyController::class, 'show']
)
    ->middleware('permission:legal.view')
    ->name('political-parties.show');


/*
|--------------------------------------------------------------------------
| Workflow History
|--------------------------------------------------------------------------
*/

Route::get(
    '/workflow-history',
    [LegalWorkflowHistoryController::class, 'index']
)
    ->middleware('permission:legal.view')
    ->name('workflow-history.index');

    Route::get(
    '/dashboard',
    [LegalDashboardController::class, 'index']
)
    ->middleware('permission:legal.view')
    ->name('dashboard');

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

            Route::get(
    '/review',
    [LegalNominationController::class, 'review']
)
    ->middleware('permission:legal.view')
    ->name('staff.legal.review.index');
    });
