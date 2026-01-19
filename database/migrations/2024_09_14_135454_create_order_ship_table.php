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
        Schema::create('order_ship', function (Blueprint $table) {
            $table->id('order_ship_id')->comment('訂單運送方式ID');
            $table->string('name')->comment('名稱');
            $table->boolean('status')->comment('狀態');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_ship');
    }
};
