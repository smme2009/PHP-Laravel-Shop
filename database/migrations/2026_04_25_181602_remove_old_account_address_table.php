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
        Schema::dropIfExists('account_address');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('account_address', function (Blueprint $table) {
            $table->id('account_address_id')->comment('帳號地址ID');
            $table->unsignedBigInteger('account_id')->index()->comment('帳號ID');
            $table->string('address')->comment('地址');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};

