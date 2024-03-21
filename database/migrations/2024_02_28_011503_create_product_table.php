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
        Schema::create('product', function (Blueprint $table) {
            $table->id('product_id')->comment('商品ID');
            $table->string('name')->comment('商品名稱');
            $table->bigInteger('photo_fid')->unsigned()->comment('商品圖片ID');
            $table->integer('price')->unsigned()->comment('商品價格');
            $table->integer('quantity')->unsigned()->comment('商品數量');
            $table->text('description')->comment('商品介紹');
            $table->boolean('status')->unsigned()->comment('狀態(0:關閉，1:開啟)');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
