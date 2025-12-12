<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use MongoDB\Laravel\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meta', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('key');
            $collection->string('value');
            $collection->string('object_id');
            $collection->string('object_type');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('stores', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('parent_id');
            $collection->string('name');
            $collection->string('location');
            $collection->string('currency_code');
            $collection->integer('active');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('users', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->array('store_ids');
            $collection->string('first_name');
            $collection->string('last_name');
            $collection->string('email')->unique();
            $collection->string('password');
            $collection->enum('role', ['SUPER_ADMIN','ADMIN','MANAGER','STAFF']);
            $collection->string('calling_code');
            $collection->string('phone_number');
            $collection->string('verify_otp')->nullable();
            $collection->string('active_on');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('images', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('object_id');
            $collection->string('collection');
            $collection->string('url');
            $collection->timestamps();
        });

        Schema::create('categories', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('parent_id');
            $collection->string('store_id');
            $collection->string('name');
            $collection->array('product_ids');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('products', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('store_id');
            $collection->string('name');
            $collection->string('sku');
            $collection->string('barcode');
            $collection->string('description');
            $collection->string('unit')->default('pcs');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('product_assigns', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('store_id');
            $collection->string('product_id');
            $collection->string('price')->default(0);
            $collection->string('cost')->default(0);
            $collection->string('quantity')->default(0);
            $collection->string('threshold')->default(0);
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('promotions', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('store_id');
            $collection->string('name');
            $collection->enum('type', ['AMOUNT','PERCENTAGE']);
            $collection->string('value')->default(0);
            $collection->datetimes('start_date');
            $collection->datetimes('end_date');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('promotion_assigns', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('promotion_id');
            $collection->string('category_id')->nullable();
            $collection->string('product_id')->nullable();
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('suppliers', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('store_id');
            $collection->string('name');
            $collection->string('email');
            $collection->string('phone_number');
            $collection->string('address');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('purchase_orders', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('store_id');
            $collection->string('user_id');
            $collection->string('order_number')->unique();
            $collection->string('supplier_name');
            $collection->string('supplier_email');
            $collection->datetimes('requested_date');
            $collection->datetimes('rejected_date');
            $collection->datetimes('recieved_date');
            $collection->enum('status', ['IN_REVIEW','REQUESTED','REJECTED','RECIEVED'])->default('IN_REVIEW');
            $collection->string('total_cost')->default(0);
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('purchase_order_details', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('purchase_order_id');
            $collection->string('product_id');
            $collection->string('quantity')->default(0);
            $collection->string('unit_cost')->default(0);
            $collection->string('total_cost')->default(0);
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('ledgers', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('stor_id');
            $collection->string('product_id');
            $collection->string('starting_quantity')->default(0);
            $collection->string('quantity')->default(0);
            $collection->string('unit_cost')->default(0);
            $collection->string('cost')->default(0);
            $collection->enum('type', ['SALE','PURCHASE_ORDER','INCREASEMENT','DECREASEMENT']);
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('printers', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('store_id');
            $collection->string('name');
            $collection->string('ip_address');
            $collection->enum('connection_type', ['LAN']);
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('histories', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('user_id');
            $collection->string('store_id');
            $collection->string('action');
            $collection->string('details');
            $collection->timestamps();
            $collection->softDeletes();
        });

        // Schema::create('orders', function (Blueprint $collection) {
        //     $collection->string('_id');
        //     $collection->string('store_id');
        //     $collection->string('sale_by');
        //     $collection->date('date');
        //     $collection->timestamps();
        //     $collection->softDeletes();
        // });

        // Schema::create('order_details', function (Blueprint $collection) {
        //     $collection->string('_id');
        //     $collection->string('order_id');
        //     $collection->string('category_id');
        //     $collection->string('product_id');
        //     $collection->date('price');
        //     $collection->date('cost');
        //     $collection->date('quantity');
        //     $collection->timestamps();
        //     $collection->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meta');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('users');
        Schema::dropIfExists('images');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_assigns');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('promotion_assigns');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('purchase_order_details');
        Schema::dropIfExists('ledgers');
        Schema::dropIfExists('printers');
        Schema::dropIfExists('histories');
    }
};
