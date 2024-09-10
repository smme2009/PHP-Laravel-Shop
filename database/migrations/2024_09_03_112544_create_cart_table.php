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
        Schema::create('cart', function (Blueprint $table) {
            $table->id('cart_id')->comment('購物車ID');
            $table->bigInteger('member_id')->comment('會員ID');
            $table->bigInteger('product_id')->comment('商品ID');
            $table->integer('quantity')->comment('數量');
            $table->timestamps(); // 時間相關欄位

            $table->unique(['member_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
