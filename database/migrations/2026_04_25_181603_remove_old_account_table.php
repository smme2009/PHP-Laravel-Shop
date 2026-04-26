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
        Schema::dropIfExists('account');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('account', function (Blueprint $table) {
            $table->id('account_id')->comment('帳號ID');
            $table->string('account')->comment('帳號(E-Mail)');
            $table->string('password')->comment('密碼');
            $table->string('name')->comment('名稱');
            $table->unsignedTinyInteger('status')->comment('狀態');
            $table->timestamps();

            $table->unique('account');
        });
    }
};

