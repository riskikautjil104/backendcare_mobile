<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerApiController;
use App\Http\Controllers\Api\HelpCenterApiController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\ThemeApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public Endpoints
    Route::get('/app-theme', [ThemeApiController::class, 'getActiveTheme']);
    Route::get('/banners', [BannerApiController::class, 'index']);
    Route::get('/help-center', [HelpCenterApiController::class, 'index']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/auth/login-pin', [AuthController::class, 'loginPin']);
    Route::post('/auth/set-pin', [AuthController::class, 'setPin']);

    // Authenticated Patient Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/user/patient-profile', [AuthController::class, 'updatePatientProfile']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // User Reservation Reference Management (No NIK)
        Route::get('/reservations', [ReservationController::class, 'index']);
        Route::post('/reservations', [ReservationController::class, 'store']);
        Route::get('/reservations/{kode}', [ReservationController::class, 'show']);
    });
});
