<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SuperAdminDashboardController;

Route::prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);

        # Super Admin Routes
        Route::group([
            'middleware' => 'role:SUPER_ADMIN',
            'prefix'     => 'super-admin',
        ], function () {
            Route::get('dashboard', [SuperAdminDashboardController::class, 'index']);
            Route::post('stores', [StoreController::class, 'store']);
        });

        # Admin Routes
        Route::group([
            'middleware' => 'role:ADMIN',
            'prefix'     => 'admin',
        ], function () {

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
