<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $collection) {
            $collection->string('promotion_id')->nullable();
        });

        Schema::table('categories', function (Blueprint $collection) {
            $collection->string('promotion_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $collection) {
            $collection->dropColumn('promotion_id');
        });

        Schema::table('categories', function (Blueprint $collection) {
            $collection->dropColumn('promotion_id');
        });
    }
};
