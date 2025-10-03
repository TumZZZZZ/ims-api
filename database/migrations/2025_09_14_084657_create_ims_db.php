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
            $collection->timestamps();
            $collection->softDeletes();
        });

        Schema::create('suppliers', function (Blueprint $collection) {
            $collection->string('_id');
            $collection->string('store_id');
            $collection->string('name');
            $collection->string('calling_code');
            $collection->string('phone_number');
            $collection->string('email')->nullable();
            $collection->string('address')->nullable();
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
