<?php

use App\Http\Controllers\Web\Nomination\EpmNominationController;
use App\Http\Controllers\Web\Nomination\EpmPrimaryMonitoringController;
use Illuminate\Support\Facades\Route;

Route::prefix('epm')
    ->name('staff.epm.')
    ->middleware(['auth', 'role:EPM Officer'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [EpmNominationController::class, 'dashboard']
        )
            ->middleware('permission:nominations.view')
            ->name('dashboard');


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

        Route::get(
            '/records',
            [EpmNominationController::class, 'records']
        )
            ->middleware('permission:nominations.view')
            ->name('records.index');

        Route::get(
            '/records/{nomination}',
            [EpmNominationController::class, 'record']
        )
            ->middleware('permission:nominations.view')
            ->name('records.show');


        /*
        |--------------------------------------------------------------------------
        | Primary Monitoring
        |--------------------------------------------------------------------------
        */

        Route::prefix('primary-monitoring')
            ->name('primary-monitoring.')
            ->group(function () {

                /*
                |----------------------------------------------------------------------
                | Primary Monitoring Index
                |----------------------------------------------------------------------
                */

                Route::get(
                    '/',
                    [EpmPrimaryMonitoringController::class, 'index']
                )
                    ->middleware('permission:primary-monitoring.view')
                    ->name('index');

                /*
                |----------------------------------------------------------------------
                | Create Primary Monitoring Record
                |----------------------------------------------------------------------
                */

                Route::get(
                    '/create',
                    [EpmPrimaryMonitoringController::class, 'create']
                )
                    ->middleware('permission:primary-monitoring.create')
                    ->name('create');

                Route::post(
                    '/',
                    [EpmPrimaryMonitoringController::class, 'store']
                )
                    ->middleware('permission:primary-monitoring.create')
                    ->name('store');


                /*
                |----------------------------------------------------------------------
                | Monitoring Reports
                |----------------------------------------------------------------------
                |
                | These MUST appear before /{primaryEvent}, otherwise Laravel
                | interprets "reports" as a PrimaryEvent ID.
                |
                */

                Route::get(
                    '/reports',
                    [EpmPrimaryMonitoringController::class, 'reports']
                )
                    ->middleware('permission:primary-monitoring.view')
                    ->name('reports.index');

                Route::get(
                    '/reports/{report}',
                    [EpmPrimaryMonitoringController::class, 'reportShow']
                )
                    ->middleware('permission:primary-monitoring.view')
                    ->name('reports.show');


                /*
                |----------------------------------------------------------------------
                | Monitor Assignment
                |----------------------------------------------------------------------
                */

                Route::post(
                    '/{primaryEvent}/assign-monitor',
                    [EpmPrimaryMonitoringController::class, 'assignMonitor']
                )
                    ->middleware('permission:primary-monitoring.assign')
                    ->name('assign-monitor');


                /*
                |----------------------------------------------------------------------
                | Submit Monitoring Report
                |----------------------------------------------------------------------
                */

                Route::post(
                    '/{primaryEvent}/submit-report',
                    [EpmPrimaryMonitoringController::class, 'submitReport']
                )
                    ->middleware('permission:primary-monitoring.view')
                    ->name('submit-report');


                /*
                |----------------------------------------------------------------------
                | Primary Event Record
                |----------------------------------------------------------------------
                |
                | Keep this dynamic route LAST.
                |
                */

                Route::get(
                    '/{primaryEvent}',
                    [EpmPrimaryMonitoringController::class, 'show']
                )
                    ->middleware('permission:primary-monitoring.view')
                    ->name('show');
            });
    });
