<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SaleScreenController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [AuthController::class, 'logout']);

        /**
         * SALE SCREEN
         */
        Route::group(['prefix' => 'sale-screen'], function() {
            Route::get('get-payment-methods', [SaleScreenController::class, 'getPaymentMethods']);
            Route::get('get-all-categories', [SaleScreenController::class, 'getAllCategories']);
            Route::get('get-all-products', [SaleScreenController::class, 'getAllProducts']);
            Route::get('get-product-by-category/{category_id}', [SaleScreenController::class, 'getProductByCategory']);
            Route::get('get-order-details', [SaleScreenController::class, 'getOrderDetails']);
            Route::post('add-order', [SaleScreenController::class, 'addOrder']);
            Route::put('adjust-order-quantity/{order_id}', [SaleScreenController::class, 'adjustOrderQuantity']);
            Route::delete('remove-item-from-order/{order_id}', [SaleScreenController::class, 'removeProductFromOrder']);
            Route::post('place-order', [SaleScreenController::class, 'placeOrder']);
        });

    });
});
