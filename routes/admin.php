<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DocumentTypeController;
use App\Http\Controllers\Admin\PoliticalPartyController;

/*
|--------------------------------------------------------------------------
| Administration
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Political Parties
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'political-parties',
            PoliticalPartyController::class
        )
        ->parameters([
            'political-parties' => 'politicalParty',
        ])
        ->except([
            'show',
            'destroy',
        ]);

        Route::patch(
            'political-parties/{politicalParty}/activate',
            [PoliticalPartyController::class, 'activate']
        )->name('political-parties.activate');

        Route::patch(
            'political-parties/{politicalParty}/deactivate',
            [PoliticalPartyController::class, 'deactivate']
        )->name('political-parties.deactivate');

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'users',
            UserController::class
        )->except([
            'destroy',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Document Types
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'document-types',
            DocumentTypeController::class
        )
        ->parameters([
            'document-types' => 'documentType',
        ])
        ->except([
            'destroy',
        ]);

        Route::patch(
            'document-types/{documentType}/activate',
            [DocumentTypeController::class, 'activate']
        )->name('document-types.activate');

        Route::patch(
            'document-types/{documentType}/deactivate',
            [DocumentTypeController::class, 'deactivate']
        )->name('document-types.deactivate');

    });
