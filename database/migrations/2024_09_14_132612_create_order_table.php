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
        Schema::create('order', function (Blueprint $table) {
            $table->id('order_id')->comment('訂單ID');
            $table->string('code')->unique()->comment('訂單編號');
            $table->string('address')->comment('地址');
            $table->bigInteger('order_ship_id')->index()->comment('運送方式ID');
            $table->bigInteger('order_payment_id')->index()->comment('付款方式ID');
            $table->bigInteger('order_status_id')->index()->comment('訂單狀態ID');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
