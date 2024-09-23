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
        Schema::create('member_address', function (Blueprint $table) {
            $table->id('member_address_id')->comment('會員地址ID');
            $table->bigInteger('member_id')->index()->comment('會員ID');
            $table->string('address')->comment('地址');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_address');
    }
};
