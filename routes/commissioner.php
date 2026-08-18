<?php

use App\Http\Controllers\Web\Nomination\CommissionerNominationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Nomination\CommissionerWithdrawalController;

Route::prefix('commissioner')
    ->name('staff.commissioner.')
    ->middleware(['auth', 'role:Commissioner'])
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

            Route::get(
    '/candidates/{candidate}',
    [CommissionerNominationController::class, 'candidate']
)
    ->middleware('permission:nominations.view')
    ->name('candidates.show');


    /*
|--------------------------------------------------------------------------
| Candidate Documents
|--------------------------------------------------------------------------
*/

Route::get(
    '/candidates/{candidate}/documents/{document}/view',
    [CommissionerNominationController::class, 'viewCandidateDocument']
)
    ->middleware('permission:nominations.view')
    ->name('candidates.documents.view');

    Route::get(
    '/candidates',
    [CommissionerNominationController::class, 'candidates']
)
    ->middleware('permission:nominations.view')
    ->name('candidates.index');
            /*
|--------------------------------------------------------------------------
| Commissioner Decision History
|--------------------------------------------------------------------------
*/

Route::get(
    '/decisions',
    [CommissionerNominationController::class, 'history']
)
    ->middleware('permission:nominations.view')
    ->name('decisions.index');


    /*
|--------------------------------------------------------------------------
| Approved Candidates Pool
|--------------------------------------------------------------------------
*/

Route::get(
    '/approved-candidates',
    [CommissionerNominationController::class, 'approvedCandidates']
)
    ->middleware('permission:nominations.view')
    ->name('approved-candidates.index');

    /*
|--------------------------------------------------------------------------
| Final Publication Approval
|--------------------------------------------------------------------------
*/

Route::get(
    '/final-publication',
    [CommissionerNominationController::class, 'finalPublication']
)
    ->middleware('permission:nominations.view')
    ->name('final-publication.index');

    Route::get(
    '/final-publication/export/excel',
    [CommissionerNominationController::class, 'exportFinalPublicationExcel']
)
    ->middleware('permission:nominations.view')
    ->name('final-publication.export.excel');

Route::get(
    '/final-publication/export/pdf',
    [CommissionerNominationController::class, 'exportFinalPublicationPdf']
)
    ->middleware('permission:nominations.view')
    ->name('final-publication.export.pdf');
    
            /*
|--------------------------------------------------------------------------
| Candidate Withdrawal Decisions
|--------------------------------------------------------------------------
*/

Route::get(
    '/withdrawals',
    [CommissionerWithdrawalController::class, 'index']
)
    ->middleware('permission:withdrawals.view')
    ->name('withdrawals.index');

    Route::get(
    '/withdrawals/{withdrawal}',
    [CommissionerWithdrawalController::class, 'show']
)
    ->middleware('permission:withdrawals.view')
    ->name('withdrawals.show');

    Route::post(
    '/withdrawals/{withdrawal}/approve',
    [CommissionerWithdrawalController::class, 'approve']
)
    ->middleware('permission:withdrawals.approve')
    ->name('withdrawals.approve');


Route::post(
    '/withdrawals/{withdrawal}/reject',
    [CommissionerWithdrawalController::class, 'reject']
)
    ->middleware('permission:withdrawals.reject')
    ->name('withdrawals.reject');

            /*
        |--------------------------------------------------------------------------
        | Shared Legal Documents
        |--------------------------------------------------------------------------
        */

        Route::get(
    '/documents',
    [\App\Http\Controllers\Web\Legal\LegalDocumentController::class, 'commissionerIndex']
)
    ->middleware('permission:nominations.view')
    ->name('documents.index');

        Route::get(
            '/documents/{legalDocument}/view',
            [\App\Http\Controllers\Web\Legal\LegalDocumentController::class, 'view']
        )
            ->middleware('permission:nominations.view')
            ->name('documents.view');

        Route::get(
            '/documents/{legalDocument}/download',
            [\App\Http\Controllers\Web\Legal\LegalDocumentController::class, 'download']
        )
            ->middleware('permission:nominations.view')
            ->name('documents.download');

    });
