<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\UploadController;

Route::prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login']);

        # Forgot Password & Verify OTP & Reset Password
        Route::post('send-mail-verification', [AuthController::class, 'sendMailVerification']);
        Route::post('verify-otp', [AuthController::class, 'verifyOTP']);
        Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'getProfile']);

        # Update Profile
        Route::group(['middleware' => 'role:ADMIN,MANAGER'], function () {
            Route::put('profile/{id}', [AuthController::class, 'updateProfile']);
        });

        Route::post('upload', [UploadController::class, 'store']);

        # Super Admin Routes
        Route::group([
            'middleware' => 'role:SUPER_ADMIN',
            'prefix'     => 'super-admin',
        ], function () {
            Route::get('dashboard', [SuperAdminDashboardController::class, 'index']);
            Route::post('stores', [StoreController::class, 'create']);
        });

        # Admin Routes
        Route::group([
            'middleware' => 'role:ADMIN',
            'prefix'     => 'admin',
        ], function () {
            Route::get('stores', [StoreController::class, 'index']);
            Route::put('stores/{id}', [StoreController::class, 'update']);

        });

        # Manager Routes
        Route::group([
            'middleware' => 'role:MANAGER',
            'prefix'     => 'manager',
        ], function () {

        });

        # Staff Routes
        Route::group([
            'middleware' => 'role:STAFF',
            'prefix'     => 'staff',
        ], function () {

        });

    });
});
