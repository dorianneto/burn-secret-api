<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('auth', [AuthController::class, 'auth']);

    Route::middleware(['auth:sanctum'])->group(function() {
        Route::get('logout', [AuthController::class, 'logout']);
    });
});
