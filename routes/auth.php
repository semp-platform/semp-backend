<?php

use App\Http\Controllers\Web\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get(
        '/login',
        [LoginController::class, 'create']
    )->name('login');

    Route::post(
        '/login',
        [LoginController::class, 'store']
    )->name('login.store');

});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [LoginController::class, 'destroy']
)->middleware('auth')->name('logout');
