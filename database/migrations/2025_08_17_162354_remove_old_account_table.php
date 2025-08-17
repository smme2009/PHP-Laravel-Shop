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
        Schema::dropIfExists('admin');
        Schema::dropIfExists('member');
        Schema::dropIfExists('member_address');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('admin', function (Blueprint $table) {
            $table->id('admin_id')->comment('管理員ID');
            $table->string('name')->comment('名稱');
            $table->string('account')->unique()->comment('帳號');
            $table->string('password')->comment('密碼');
            $table->unsignedTinyInteger('status')->comment('狀態');
            $table->timestamps();
        });

        Schema::create('member', function (Blueprint $table) {
            $table->id('member_id')->comment('會員ID');
            $table->string('name')->comment('名稱');
            $table->string('phone')->unique()->comment('手機號碼');
            $table->string('account')->unique()->comment('帳號');
            $table->string('password')->comment('密碼');
            $table->unsignedTinyInteger('status')->comment('狀態');
            $table->timestamps();
        });

        Schema::create('member_address', function (Blueprint $table) {
            $table->id('member_address_id')->comment('會員地址ID');
            $table->bigInteger('member_id')->index()->comment('會員ID');
            $table->string('address')->comment('地址');
            $table->timestamps();
        });
    }
};
