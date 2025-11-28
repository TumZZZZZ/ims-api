<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ErrorsController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SuperAdminController;

Route::middleware('web')->group(function () {

    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/force-logout', [LoginController::class, 'logout'])->name('froce.logout');
    Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot.password');
    Route::post('/send-otp', [LoginController::class, 'sendOTP'])->name('send.otp');
    Route::get('/verify-otp', [LoginController::class, 'verifyOTPForm'])->name('verify.otp.form');
    Route::post('/verify-otp-code', [LoginController::class, 'verifyOTPCode'])->name('verify.otp.code');
    Route::get('/reset-password/{id}', [LoginController::class, 'resetPasswordForm'])->name('reset.password.form');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('reset.password');

    Route::prefix('super-admin')->middleware('role:SUPER_ADMIN')->group(function () {
        Route::get('dashboard', [SuperAdminController::class, 'dashboard'])->name('super-admin.dashboard');
        Route::get('merchants', [SuperAdminController::class, 'getMerchants'])->name('super-admin.merchants');
        Route::get('branches', [SuperAdminController::class, 'getBranches'])->name('super-admin.branches');
        Route::get('users', [SuperAdminController::class, 'getUsers'])->name('super-admin.users');
        Route::get('activity-logs', [SuperAdminController::class, 'getActivityLogs'])->name('super-admin.activity-logs');
    });

    Route::prefix('admin')->middleware('role:ADMIN')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('category-list', [AdminController::class, 'listCategory'])->name('admin.category.list');
        Route::get('category-create', [AdminController::class, 'createCategory'])->name('admin.category.create');
        Route::get('product-list', [AdminController::class, 'productList'])->name('admin.product.list');
    });

    Route::prefix('manager')->middleware('role:MANAGER')->group(function () {
        Route::get('dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
    });

    Route::prefix('errors')->group(function () {
        Route::get('401', [ErrorsController::class, 'unauthorized'])->name('401.page');
        Route::get('403', [ErrorsController::class, 'forbidden'])->name('403.page');
        Route::get('404', [ErrorsController::class, 'pageNotFound'])->name('404.page');
    });
});
