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
        Schema::create('member', function (Blueprint $table) {
            $table->id('member_id')->comment('使用者ID');
            $table->string('name')->nullable()->comment('名稱');
            $table->string('account')->unique()->comment('帳號');
            $table->string('password')->comment('密碼');
            $table->boolean('status')->comment('狀態(0:關閉，1:開啟)');
            $table->timestamps(); // 時間相關欄位
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member');
    }
};
