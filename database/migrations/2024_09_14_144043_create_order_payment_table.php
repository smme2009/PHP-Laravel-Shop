<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_payment', function (Blueprint $table) {
            $table->id('order_payment_id')->comment('訂單付款方式ID');
            $table->string('name')->comment('名稱');
            $table->boolean('status')->comment('狀態');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('order_payment')->insert([
            [
                'order_payment_id' => 1,
                'name' => '貨到付款',
                'status' => 1,
            ],
            [
                'order_payment_id' => 2,
                'name' => '信用卡',
                'status' => 1,
            ],
            [
                'order_payment_id' => 3,
                'name' => '行動支付',
                'status' => 1,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payment');
    }
};
