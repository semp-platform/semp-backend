<?php

use App\Http\Controllers\Web\Nomination\IctNominationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Party\CandidateDocumentController;

/*
|--------------------------------------------------------------------------
| ICT Department
|--------------------------------------------------------------------------
*/

Route::prefix('ict')
    ->name('staff.ict.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Incoming Nomination Batches
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/nomination-batches',
            [IctNominationController::class, 'batches']
        )

            ->name('nomination-batches.index');

        /*
        |--------------------------------------------------------------------------
        | Batch Details
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/nomination-batches/{batch}',
            [IctNominationController::class, 'showBatch']
        )
            ->middleware('permission:nominations.view')
            ->name('nomination-batches.show');

        /*
        |--------------------------------------------------------------------------
        | Receive Batch
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/nomination-batches/{batch}/receive',
            [IctNominationController::class, 'receiveBatch']
        )
            ->middleware('permission:nominations.review')
            ->name('nomination-batches.receive');

            /*
|--------------------------------------------------------------------------
| View Candidate Document
|--------------------------------------------------------------------------
*/

Route::get(
    '/candidate-documents/{document}/view',
    [CandidateDocumentController::class, 'view']
)
    ->middleware('permission:nominations.view')
    ->name('candidate-documents.view');

        /*
        |--------------------------------------------------------------------------
        | Nomination Details
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/nominations/{nomination}',
            [IctNominationController::class, 'show']
        )
            ->middleware('permission:nominations.view')
            ->name('nominations.show');

        /*
        |--------------------------------------------------------------------------
        | Forward
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/nominations/{nomination}/forward',
            [IctNominationController::class, 'forward']
        )
            ->middleware('permission:nominations.review')
            ->name('nominations.forward');

        /*
        |--------------------------------------------------------------------------

        |--------------------------------------------------------------------
|--------------------------------------------------------------------------
| Nominations Currently With ICT
|--------------------------------------------------------------------------
*/

Route::get(
    '/nominations',
    [IctNominationController::class, 'index']
)
    ->middleware('permission:nominations.view')
    ->name('nominations.index');


    });
