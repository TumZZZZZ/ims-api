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
        Schema::create('stores', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('name');
            $collection->string('location');
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('users', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('store_id')->nullable();
            $collection->string('first_name');
            $collection->string('last_name');
            $collection->string('email')->unique();
            $collection->string('password');
            $collection->enum('role', ['SUPER_ADMIN','ADMIN','MANAGER','STAFF']);
            $collection->string('calling_code');
            $collection->string('phone_number');
            $collection->string('verify_otp')->nullable();
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
            $collection->string('name');
            $collection->string('barcode')->nullable();
            $collection->string('description')->nullable();
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('product_assigns', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('store_id');
            $collection->string('product_id');
            $collection->string('price')->default(0);
            $collection->string('cost')->default(0);
            $collection->string('unit')->default('pcs');
            $collection->string('quantity')->default(0);
            $collection->string('threshold')->default(0);
            $collection->timestamps();
            $collection->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store');
        Schema::dropIfExists('user');
        Schema::dropIfExists('supplier');
    }
};
