<?php

use App\Http\Controllers\Web\Election\ElectionController;
use App\Http\Controllers\Web\Public\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Elections
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Public Website Homepage
|--------------------------------------------------------------------------
|
| Guests see the public OGSIEC website.
| Authenticated SEMP users are sent to their appropriate portal.
|
*/


/*
|--------------------------------------------------------------------------
| Authenticated Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    function () {
        return view('dashboard');
    }
)->name('dashboard');


/*
|--------------------------------------------------------------------------
| Staff Election Management
|--------------------------------------------------------------------------
*/

Route::get(
    '/elections',
    [ElectionController::class, 'index']
)
->middleware('permission:elections.view')
->name('elections.index');

Route::get(
    '/elections/create',
    [ElectionController::class, 'create']
)
->middleware('permission:elections.create')
->name('elections.create');

Route::post(
    '/elections',
    [ElectionController::class, 'store']
)
->middleware('permission:elections.create')
->name('elections.store');

Route::get(
    '/elections/{election}',
    [ElectionController::class, 'show']
)
->middleware('permission:elections.view')
->name('elections.show');

Route::get(
    '/elections/{election}/edit',
    [ElectionController::class, 'edit']
)
->middleware('permission:elections.update')
->name('elections.edit');

Route::put(
    '/elections/{election}',
    [ElectionController::class, 'update']
)
->middleware('permission:elections.update')
->name('elections.update');

Route::patch(
    '/elections/{election}/open-nominations',
    [ElectionController::class, 'openNominations']
)
->middleware('permission:elections.update')
->name('elections.open-nominations');

Route::patch(
    '/elections/{election}/start-screening',
    [ElectionController::class, 'startScreening']
)
->middleware('permission:elections.update')
->name('elections.start-screening');

Route::patch(
    '/elections/{election}/complete',
    [ElectionController::class, 'complete']
)
->middleware('permission:elections.update')
->name('elections.complete');

Route::patch(
    '/elections/{election}/archive',
    [ElectionController::class, 'archive']
)
->middleware('permission:elections.update')
->name('elections.archive');
