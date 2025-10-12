<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ManagerController;

Route::middleware('web')->group(function () {

    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('super-admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('super-admin.dashboard');
    });

    Route::prefix('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('product-list', [AdminController::class, 'productList'])->name('admin.product.list');
    });

    Route::prefix('mamager')->group(function () {
        Route::get('dashboard', [ManagerController::class, 'dashboard'])->name('mamager.dashboard');
    });
});
