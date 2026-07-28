<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\Reference\StateController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Reference\LgaController;
use App\Http\Controllers\Api\Reference\WardController;
use App\Http\Controllers\Api\Election\ElectionController;

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

Route::post('/elections', [ElectionController::class, 'store']);
