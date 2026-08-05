<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\Reference\StateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Reference\LgaController;
use App\Http\Controllers\Api\Reference\WardController;
use App\Http\Controllers\Api\Election\ElectionController;
use App\Http\Controllers\Api\Nomination\NominationController;
use App\Http\Controllers\Api\Reference\LcdaController;


// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/states', [StateController::class, 'index']);


// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);

});

Route::get('/lgas', [LgaController::class, 'index']);
Route::get('/states/{state}/lgas', [LgaController::class, 'byState']);
Route::get('/lgas/{lga}/wards', [WardController::class, 'byLga']);
Route::get( 'lgas/{lga}/lcdas', [LcdaController::class, 'byLga']);

Route::post('/elections', [ElectionController::class, 'store']);
Route::get('/elections', [ElectionController::class, 'index']);

Route::get('/elections/{election}', [ElectionController::class, 'show']);
Route::post('/nominations', [NominationController::class, 'store']);
Route::get('/nominations', [NominationController::class, 'index']);

Route::get('/nominations/{nomination}', [NominationController::class, 'show']);


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });




});
