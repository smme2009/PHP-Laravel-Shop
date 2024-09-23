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
        Schema::create('order_status', function (Blueprint $table) {
            $table->id('order_status_id')->comment('訂單狀態ID');
            $table->string('name')->comment('名稱');
            $table->boolean('status')->comment('狀態');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('order_status')->insert([
            [
                'order_status_id' => 1,
                'name' => '訂單成立',
                'status' => 1,
            ],
            [
                'order_status_id' => 2,
                'name' => '練貨中',
                'status' => 1,
            ],
            [
                'order_status_id' => 3,
                'name' => '理貨中',
                'status' => 1,
            ],
            [
                'order_status_id' => 4,
                'name' => '出貨中',
                'status' => 1,
            ],
            [
                'order_status_id' => 5,
                'name' => '已送達',
                'status' => 1,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status');
    }
};
