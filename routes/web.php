<?php

use App\Http\Controllers\Web\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Election\ElectionController;
use App\Http\Controllers\Web\Nomination\NominationController;


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [LoginController::class, 'destroy'])
        ->name('logout');
        Route::get('/elections', [ElectionController::class, 'index'])
    ->name('elections.index');

Route::get('/elections/{election}', [ElectionController::class, 'show'])
    ->name('elections.show');
    Route::get('/nominations', [NominationController::class, 'index'])
    ->name('nominations.index');

Route::get('/nominations/{nomination}', [NominationController::class, 'show'])
    ->name('nominations.show');
});
