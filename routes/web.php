<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SuperAdminController;

Route::middleware('web')->group(function () {

    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot.password');
    Route::post('/send-otp', [LoginController::class, 'sendOTP'])->name('send.otp');
    Route::get('/verify-otp', [LoginController::class, 'verifyOTPForm'])->name('verify.otp.form');
    Route::post('/verify-otp-code', [LoginController::class, 'verifyOTPCode'])->name('verify.otp.code');
    Route::get('/reset-password/{id}', [LoginController::class, 'resetPasswordForm'])->name('reset.password.form');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('reset.password');

    Route::prefix('super-admin')->group(function () {
        Route::get('dashboard', [SuperAdminController::class, 'dashboard'])->name('super-admin.dashboard');
        Route::get('stores', [SuperAdminController::class, 'listStore'])->name('super-admin.stores');
        Route::get('users', [SuperAdminController::class, 'listUser'])->name('super-admin.users');
    });

    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('category-list', [AdminController::class, 'listCategory'])->name('admin.category.list');
        Route::get('category-create', [AdminController::class, 'createCategory'])->name('admin.category.create');
        Route::get('product-list', [AdminController::class, 'productList'])->name('admin.product.list');
    });

    Route::prefix('manager')->group(function () {
        Route::get('dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
    });
});
