<?php

use Illuminate\Support\Facades\Route;
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
use App\Http\Controllers\Admin\{
    BaseAdminController,
    BranchController as AdminBranchController,
    CategoryController,
    ProductController,
    PromotionController,
    UserController as AdminUserController
};
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Inventory\{
    SupplierController,
    PurchaseOrderController,
    LedgersController
};
use App\Http\Controllers\SettingController;

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
        Route::get('dashboard', [BaseAdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::group(['prefix' => 'branches'], function () {
            Route::get('/', [AdminBranchController::class, 'index'])->name('admin.branches.index');
            Route::get('create', [AdminBranchController::class, 'create'])->name('admin.branch.create');
            Route::post('store', [AdminBranchController::class, 'store'])->name('admin.branch.store');
            Route::get('edit/{id}', [AdminBranchController::class, 'edit'])->name('admin.branch.edit');
            Route::put('update/{id}', [AdminBranchController::class, 'update'])->name('admin.branch.update');
            Route::delete('delete/{id}', [AdminBranchController::class, 'delete'])->name('admin.branch.delete');
            Route::post('{id}/close-or-open', [AdminBranchController::class, 'closeOrOpen']);
        });
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [AdminUserController::class, 'index'])->name('admin.users.index');
            Route::get('create', [AdminUserController::class, 'create'])->name('admin.user.create');
            Route::post('store', [AdminUserController::class, 'store'])->name('admin.user.store');
            Route::get('edit/{product_id}', [AdminUserController::class, 'edit'])->name('admin.user.edit');
            Route::put('update/{id}', [AdminUserController::class, 'update'])->name('admin.user.update');
            Route::delete('delete/{id}', [AdminUserController::class, 'delete'])->name('admin.user.delete');
        });
        Route::group(['prefix' => 'categories'], function () {
            Route::get('/', [CategoryController::class, 'index'])->name('admin.categories.index');
            Route::get('create', [CategoryController::class, 'create'])->name('admin.category.create');
            Route::post('store', [CategoryController::class, 'store'])->name('admin.category.store');
            Route::get('edit/{category_id}', [CategoryController::class, 'edit'])->name('admin.category.edit');
            Route::put('update/{category_id}', [CategoryController::class, 'update'])->name('admin.category.update');
            Route::delete('delete/{category_id}', [CategoryController::class, 'delete'])->name('admin.category.delete');
        });
        Route::group(['prefix' => 'products'], function () {
            Route::get('/', [ProductController::class, 'index'])->name('admin.products.index');
            Route::get('create', [ProductController::class, 'create'])->name('admin.product.create');
            Route::post('store', [ProductController::class, 'store'])->name('admin.product.store');
            Route::get('edit/{product_id}', [ProductController::class, 'edit'])->name('admin.product.edit');
            Route::put('update/{product_id}', [ProductController::class, 'update'])->name('admin.product.update');
            Route::delete('delete/{product_id}', [ProductController::class, 'delete'])->name('admin.product.delete');
        });
        Route::group(['prefix' => 'promotions'], function () {
            Route::get('/', [PromotionController::class, 'index'])->name('admin.promotions.index');
            Route::get('create', [PromotionController::class, 'create'])->name('admin.promotion.create');
            Route::post('store', [PromotionController::class, 'store'])->name('admin.promotion.store');
            Route::get('edit/{id}', [PromotionController::class, 'edit'])->name('admin.promotion.edit');
            Route::put('update/{id}', [PromotionController::class, 'update'])->name('admin.promotion.update');
            Route::delete('delete/{id}', [PromotionController::class, 'delete'])->name('admin.promotion.delete');
        });
        Route::group(['prefix' => 'setting'], function() {
            Route::get('telegram-config', [SettingController::class, 'telegramConfig'])->name('setting.telegram-config');
            Route::post('setup-config', [SettingController::class, 'setupConfig'])->name('setting.setup-config');
            Route::post('send-test', [SettingController::class, 'sendTest'])->name('setting.send-test');
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
     *                  INVENTORY
     * =================================================
     */
    Route::prefix('inventory')->middleware('role:ADMIN|MANAGER')->group(function () {

        // Suppliers routes
        Route::prefix('suppliers')->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('inventory.suppliers.index');
            Route::get('create', [SupplierController::class, 'create'])->name('inventory.supplier.create');
            Route::post('store', [SupplierController::class, 'store'])->name('inventory.supplier.store');
            Route::get('edit/{id}', [SupplierController::class, 'edit'])->name('inventory.supplier.edit');
            Route::put('update/{id}', [SupplierController::class, 'update'])->name('inventory.supplier.update');
            Route::delete('delete/{id}', [SupplierController::class, 'delete'])->name('inventory.supplier.delete');
        });

        // Purchase Orders routes
        Route::prefix('purchase-orders')->group(function () {
            Route::get('closed', [PurchaseOrderController::class, 'closed'])->name('inventory.purchase-orders.closed');
            Route::get('draft', [PurchaseOrderController::class, 'draft'])->name('inventory.purchase-orders.draft');
            Route::get('sent', [PurchaseOrderController::class, 'sent'])->name('inventory.purchase-orders.sent');
            Route::get('rejected', [PurchaseOrderController::class, 'rejected'])->name('inventory.purchase-orders.rejected');
            Route::get('create', [PurchaseOrderController::class, 'create'])->name('inventory.purchase-order.create');
            Route::post('store', [PurchaseOrderController::class, 'store'])->name('inventory.purchase-order.store');
            Route::get('view-details/{id}', [PurchaseOrderController::class, 'viewDetails'])->name('inventory.purchase-order.view-details');
            Route::put('update/{id}', [PurchaseOrderController::class, 'update'])->name('inventory.purchase-order.update');
            Route::post('reject/{id}', [PurchaseOrderController::class, 'reject'])->name('inventory.purchase-order.reject');
            Route::get('edit/{id}', [PurchaseOrderController::class, 'edit'])->name('inventory.purchase-order.edit');
            Route::delete('delete/{id}', [PurchaseOrderController::class, 'delete'])->name('inventory.purchase-order.destroy');
        });

        // Ledgers routes
        Route::prefix('ledgers')->group(function () {
            Route::get('/', [LedgersController::class, 'index'])->name('inventory.ledgers.index');
        });
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


    /**
     * ================================================
     *                  API CHAT BOT
     * ================================================
     */
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
});
