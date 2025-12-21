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
        Schema::create('metas', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('key');
            $collection->integer('value');
            $collection->string('object_id');
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
            $collection->string('first_name');
            $collection->string('last_name');
            $collection->string('email')->unique();
            $collection->string('password');
            $collection->enum('role', ['SUPER_ADMIN','ADMIN','MANAGER','STAFF']);
            $collection->string('calling_code');
            $collection->string('phone_number');
            $collection->string('verify_otp');
            $collection->string('active_on');
            $collection->string('merchant_id');
            $collection->array('branch_ids');
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
            $collection->string('name');
            $collection->array('branch_ids');
            $collection->array('product_ids');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('products', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('name');
            $collection->string('sku');
            $collection->string('barcode');
            $collection->string('description');
            $collection->array('category_ids');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('product_assigns', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('branch_id');
            $collection->string('product_id');
            $collection->integer('price')->default(0);
            $collection->integer('cost')->default(0);
            $collection->integer('quantity')->default(0);
            $collection->integer('threshold')->default(0);
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('promotions', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('branch_id');
            $collection->string('name');
            $collection->enum('type', ['AMOUNT','PERCENTAGE']);
            $collection->string('value')->default(0);
            $collection->datetimes('start_date');
            $collection->datetimes('end_date');
            $collection->array('category_ids');
            $collection->array('product_ids');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('suppliers', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('merchant_id');
            $collection->string('name');
            $collection->string('email');
            $collection->string('calling_code');
            $collection->string('phone_number');
            $collection->string('address');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('purchase_orders', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('branch_id');
            $collection->string('user_id');
            $collection->string('supplier_id');
            $collection->string('order_number')->unique();
            $collection->datetimes('requested_date');
            $collection->datetimes('rejected_date');
            $collection->datetimes('recieved_date');
            $collection->enum('status', ['IN_REVIEW','REQUESTED','REJECTED','RECIEVED'])->default('IN_REVIEW');
            $collection->integer('total_cost')->default(0);
            $collection->string('reason');
            $collection->array('purchase_order_details'); // Object{product_id,quantity,unit_cost,total_cost}
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('ledgers', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('branch_id');
            $collection->string('product_id');
            $collection->integer('starting_quantity')->default(0);
            $collection->integer('quantity')->default(0);
            $collection->integer('unit_cost')->default(0);
            $collection->integer('cost')->default(0);
            $collection->enum('type', ['SALE','PURCHASE_ORDER','INCREASEMENT','DECREASEMENT']);
            $collection->string('reason');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('printers', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('branch_id');
            $collection->string('name');
            $collection->string('ip_address');
            $collection->enum('connection_type', ['LAN']);
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('histories', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('user_id');
            $collection->string('merchant_id');
            $collection->string('branch_id');
            $collection->string('action');
            $collection->string('details');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('orders', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('branch_id');
            $collection->string('payment_id');
            $collection->string('sale_by'); // objectId of user
            $collection->integer('order_number');
            $collection->date('date');
            $collection->enum('status', ['NEW','CANCELLED','PAID']);
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('order_details', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('order_id');
            $collection->string('category_id');
            $collection->string('product_id');
            $collection->string('discount_id');
            $collection->integer('price');
            $collection->integer('cost');
            $collection->integer('quantity');
            $collection->integer('discount_amount');
            $collection->timestamps();
            $collection->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metas');
        Schema::dropIfExists('stores');
        Schema::dropIfExists('users');
        Schema::dropIfExists('images');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_assigns');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('ledgers');
        Schema::dropIfExists('printers');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('order_details');
        Schema::dropIfExists('histories');
    }
};
