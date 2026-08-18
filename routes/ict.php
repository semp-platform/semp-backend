<?php

use App\Http\Controllers\Web\Nomination\IctNominationController;
use App\Http\Controllers\Web\Party\CandidateDocumentController;
use App\Http\Controllers\Web\Results\IctResultController;
use Illuminate\Support\Facades\Route;

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
        | Forward Nomination
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
        | Nominations Currently With ICT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/nominations',
            [IctNominationController::class, 'index']
        )
            ->middleware('permission:nominations.view')
            ->name('nominations.index');

        /*
        |--------------------------------------------------------------------------
        | Election Results
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/results',
            [IctResultController::class, 'index']
        )
            ->middleware('permission:results.manage')
            ->name('results.index');

        Route::get(
            '/results/create',
            [IctResultController::class, 'create']
        )
            ->middleware('permission:results.manage')
            ->name('results.create');

        Route::post(
            '/results/analyse',
            [IctResultController::class, 'analyse']
        )
            ->middleware('permission:results.manage')
            ->name('results.analyse');

        Route::post(
            '/results/import',
            [IctResultController::class, 'import']
        )
            ->middleware('permission:results.manage')
            ->name('results.import');

            Route::post(
    '/results/{resultImport}/publish',
    [IctResultController::class, 'publish']
)
    ->middleware('permission:results.manage')
    ->name('results.publish');

        Route::get(
            '/results/{resultImport}',
            [IctResultController::class, 'show']
        )
            ->middleware('permission:results.manage')
            ->name('results.show');

        Route::get(
            '/results/{resultImport}/ward/{ward}',
            [IctResultController::class, 'ward']
        )
            ->middleware('permission:results.manage')
            ->name('results.ward');
    });
