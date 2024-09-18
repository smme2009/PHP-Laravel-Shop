<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->integer('order_ship_price')->after('order_payment_id')->comment('運費');
            $table->integer('order_product_total')->after('order_ship_price')->comment('商品總計');
            $table->integer('order_total')->after('order_product_total')->comment('訂單總計');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('order_ship_price');
            $table->dropColumn('order_product_total');
            $table->dropColumn('order_total');
        });
    }
};
