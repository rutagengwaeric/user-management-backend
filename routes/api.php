<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// routes/api.php
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Citizen routes
    Route::apiResource('citizens', CitizenController::class);
    Route::get('/citizens/my-profile', [CitizenController::class, 'myProfile']);
    Route::patch('/citizens/{id}/verify', [CitizenController::class, 'verify']);
    
    // User management (system admin only)
    Route::apiResource('users', UserController::class)->except(['store']);
    
    // Analytics (policy maker only)
    Route::get('/analytics/stats', [AnalyticsController::class, 'getStats']);
    Route::get('/analytics/verification-trends', [AnalyticsController::class, 'getVerificationTrends']);
    Route::get('/analytics/demographics', [AnalyticsController::class, 'getDemographics']);
});
