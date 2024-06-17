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
        Schema::create('product_type', function (Blueprint $table) {
            $table->id('product_type_id')->comment('商品類型ID');
            $table->string('name')->comment('商品類型名稱');
            $table->boolean('status')->unsigned()->comment('狀態(0:關閉，1:開啟)');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_type');
    }
};
