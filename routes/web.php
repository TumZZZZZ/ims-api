<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ErrorsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SuperAdminController;

Route::middleware('web')->group(function () {

    Route::get('/', fn() => redirect()->route('login'));
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('select-branch', [LoginController::class, 'showSelectBranch'])->name('select.branch');
    Route::post('select-branch', [LoginController::class, 'selectBranch'])->name('select.branch.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/force-logout', [LoginController::class, 'logout'])->name('froce.logout');
    Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('forgot.password');
    Route::post('/send-otp', [LoginController::class, 'sendOTP'])->name('send.otp');
    Route::get('/verify-otp', [LoginController::class, 'verifyOTPForm'])->name('verify.otp.form');
    Route::post('/verify-otp-code', [LoginController::class, 'verifyOTPCode'])->name('verify.otp.code');
    Route::get('/reset-password/{id}', [LoginController::class, 'resetPasswordForm'])->name('reset.password.form');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('reset.password');

    /**
     * =================================================
     *                  SUPER ADMIN
     * =================================================
     */
    Route::prefix('super-admin')->middleware('role:SUPER_ADMIN')->group(function () {
        // Dashboard
        Route::get('dashboard', [SuperAdminController::class, 'dashboard'])->name('super-admin.dashboard');

        // Merchants
        Route::group(['prefix' => 'merchants'], function () {
            Route::get('/', [SuperAdminController::class, 'getMerchants'])->name('super-admin.merchants');
            Route::post('/{merchant_id}/suspend-or-activate', [SuperAdminController::class, 'suspendOrActivate']);
            Route::get('create', [SuperAdminController::class, 'createMerchantForm'])->name('super-admin.merchants.create.form');
            Route::post('store', [SuperAdminController::class, 'storeMerchant'])->name('super-admin.merchants.store');
            Route::get('update/{merchant_id}', [SuperAdminController::class, 'updateMerchantForm'])->name('super-admin.merchants.update.form');
            Route::put('update/{merchant_id}', [SuperAdminController::class, 'updateMerchant'])->name('super-admin.merchants.update');
            Route::delete('delete/{merchant_id}', [SuperAdminController::class, 'deleteMerchant'])->name('super-admin.merchants.delete');
        });

        Route::get('branches', [SuperAdminController::class, 'getBranches'])->name('super-admin.branches');
        Route::get('users', [SuperAdminController::class, 'getUsers'])->name('super-admin.users');
        Route::get('activity-logs', [SuperAdminController::class, 'getActivityLogs'])->name('super-admin.activity-logs');
    });

    /**
     * =================================================
     *                  ADMIN
     * =================================================
     */
    Route::prefix('admin')->middleware('role:ADMIN')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [AdminController::class, 'getCategories'])->name('admin.categories');
            Route::get('create', [AdminController::class, 'createCategoryForm'])->name('admin.category.create');
            Route::post('store', [AdminController::class, 'storeCategory'])->name('admin.category.store');
            Route::get('edit/{category_id}', [AdminController::class, 'editCategoryForm'])->name('admin.category.edit');
            Route::put('update/{category_id}', [AdminController::class, 'updateCategory'])->name('admin.category.update');
            Route::delete('delete/{category_id}', [AdminController::class, 'deleteCategory'])->name('admin.category.delete');
        });
        Route::group(['prefix' => 'products'], function () {
            Route::get('/', [AdminController::class, 'getProducts'])->name('admin.products');
            Route::get('create', [AdminController::class, 'createProductForm'])->name('admin.product.create');
            Route::post('store', [AdminController::class, 'storeProduct'])->name('admin.product.store');
            Route::get('edit/{product_id}', [AdminController::class, 'editProductForm'])->name('admin.product.edit');
            Route::put('update/{product_id}', [AdminController::class, 'updateProduct'])->name('admin.product.update');
            Route::delete('delete/{product_id}', [AdminController::class, 'deleteProduct'])->name('admin.product.delete');
        });
    });

    /**
     * =================================================
     *                  MANAGER
     * =================================================
     */
    Route::prefix('manager')->middleware('role:MANAGER')->group(function () {
        Route::get('dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
    });

    /**
     * =================================================
     *                 LANGUAGE SWITCHER
     * =================================================
     */
    Route::get('lang/{locale}', [LanguageController::class, 'switchLanguage'])->name('language.switch');

    /**
     * =================================================
     *                  HANDLING ERRORS
     * =================================================
     */
    Route::prefix('errors')->group(function () {
        Route::get('401', [ErrorsController::class, 'unauthorized'])->name('401.page');
        Route::get('403', [ErrorsController::class, 'forbidden'])->name('403.page');
        Route::get('404', [ErrorsController::class, 'pageNotFound'])->name('404.page');
    });
});
