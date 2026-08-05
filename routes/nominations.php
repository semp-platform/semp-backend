<?php

use App\Http\Controllers\Web\Nomination\NominationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| OGSIEC Nominations
|--------------------------------------------------------------------------
*/

Route::get(
    '/nominations',
    [NominationController::class, 'index']
)
->middleware('permission:nominations.view')
->name('nominations.index');

Route::get(
    '/nominations/{nomination}',
    [NominationController::class, 'show']
)
->middleware('permission:nominations.view')
->name('nominations.show');
