<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ErrorsController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SuperAdmin\{
    ActivityLogController,
    BranchController,
    MerchantController,
    SuperAdminBaseController,
    UserController
};

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
        Route::get('dashboard', [SuperAdminBaseController::class, 'dashboard'])->name('super-admin.dashboard');

        // Merchants
        Route::group(['prefix' => 'merchants'], function () {
            Route::get('/', [MerchantController::class, 'index'])->name('super-admin.merchants.index');
            Route::post('/{merchant_id}/suspend-or-activate', [MerchantController::class, 'suspendOrActivate']);
            Route::get('create', [MerchantController::class, 'create'])->name('super-admin.merchant.create');
            Route::post('store', [MerchantController::class, 'store'])->name('super-admin.merchant.store');
            Route::get('edit/{merchant_id}', [MerchantController::class, 'edit'])->name('super-admin.merchant.edit');
            Route::put('update/{merchant_id}', [MerchantController::class, 'update'])->name('super-admin.merchant.update');
            Route::delete('delete/{merchant_id}', [MerchantController::class, 'delete'])->name('super-admin.merchant.delete');
        });

        // Branches
        Route::group(['prefix' => 'branches'], function() {
            Route::get('/', [BranchController::class, 'index'])->name('super-admin.branches.index');
        });

        // Users
        Route::group(['prefix' => 'users'], function() {
            Route::get('/', [UserController::class, 'index'])->name('super-admin.users.index');
        });

        // Activities
        Route::group(['prefix' => 'activity-logs'], function() {
            Route::get('/', [ActivityLogController::class, 'index'])->name('super-admin.activity-logs.index');
        });
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
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [AdminController::class, 'getUsers'])->name('admin.users');
            Route::get('create', [AdminController::class, 'createUserForm'])->name('admin.user.create');
            Route::post('store', [AdminController::class, 'storeUser'])->name('admin.user.store');
            Route::get('edit/{product_id}', [AdminController::class, 'editUserForm'])->name('admin.user.edit');
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
