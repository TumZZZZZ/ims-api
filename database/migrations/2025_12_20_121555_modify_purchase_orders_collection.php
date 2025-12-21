<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchase_orders', function (Blueprint $collection) {

            if (!Schema::hasColumn('purchase_orders', 'payment_term')) {
                $collection->string('payment_term')->nullable();
            }

            if (!Schema::hasColumn('purchase_orders', 'shipping_carrier')) {
                $collection->string('shipping_carrier')->nullable();
            }

            if (!Schema::hasColumn('purchase_orders', 'shipping_fee')) {
                $collection->integer('shipping_fee')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $collection) {
            $collection->dropColumn([
                'payment_term',
                'shipping_carrier',
                'shipping_fee',
            ]);
        });
    }
};
