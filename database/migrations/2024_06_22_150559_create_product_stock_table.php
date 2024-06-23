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
        Schema::create('product_stock', function (Blueprint $table) {
            $table->id('product_stock_id')->comment('商品庫存單ID');
            $table->bigInteger('product_stock_type_id')->comment('商品庫存單類型ID');
            $table->bigInteger('product_id')->comment('商品ID');
            $table->integer('quantity')->unsigned()->comment('數量');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock');
    }
};
