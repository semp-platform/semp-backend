<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\Reference\StateController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/states', [StateController::class, 'index']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);

});
