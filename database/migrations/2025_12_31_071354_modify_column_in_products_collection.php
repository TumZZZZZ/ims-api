<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $collection) {
            // Drop old array column
            $collection->dropColumn('category_ids');

            // Add new string column
            $collection->string('category_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $collection) {
            // Rollback
            $collection->dropColumn('category_id');
            $collection->array('category_ids')->nullable();
        });
    }
};
