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
        Schema::create('account_role', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->comment('帳號ID');
            $table->unsignedBigInteger('role_id')->comment('角色ID');
            $table->dateTime('created_at')->nullable()->comment('新增時間');

            $table->primary(['account_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_role');
    }
};
