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
        Schema::create('order_product', function (Blueprint $table) {
            $table->id('order_product_id')->comment('訂單商品ID');
            $table->bigInteger('order_id')->index()->comment('訂單ID');
            $table->bigInteger('product_id')->index()->comment('商品ID');
            $table->bigInteger('photo_fid')->index()->commont('商品圖片ID');
            $table->string('name')->comment('商品名稱');
            $table->integer('quantity')->comment('數量');
            $table->integer('price')->comment('價格');
            $table->integer('total')->comment('總價');
            $table->json('original_product')->comment('原始商品資料');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
