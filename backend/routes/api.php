<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TravelRequestController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::get('/ping', function () {
    return response()->json(['message' => 'pong']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/travel-requests', [TravelRequestController::class, 'index']);
    Route::post('/travel-requests', [TravelRequestController::class, 'store']);
    Route::get('/travel-requests/{id}', [TravelRequestController::class, 'show']);
    Route::put('/travel-requests/{id}/cancel', [TravelRequestController::class, 'cancel']);

    Route::middleware('admin')->group(function () {
        Route::put('/travel-requests/{id}/status', [TravelRequestController::class, 'updateStatus']);
    });
});
