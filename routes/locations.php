<?php

use App\Http\Controllers\Web\Reference\LocationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Locations
|--------------------------------------------------------------------------
*/

Route::prefix('locations')
    ->name('locations.')
    ->group(function () {

        Route::get(
            '/states/{state}/lgas',
            [LocationController::class, 'lgas']
        )->name('lgas');

        Route::get(
            '/lgas/{lga}/wards',
            [LocationController::class, 'wards']
        )->name('wards');

        Route::get(
            '/lgas/{lga}/lcdas',
            [LocationController::class, 'lcdas']
        )->name('lcdas');
        Route::get(
    '/lcdas/{lcda}/wards',
    [LocationController::class, 'lcdaWards']
)->name('lcda.wards');

    });
