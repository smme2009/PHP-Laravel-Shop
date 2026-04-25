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
        Schema::dropIfExists('role');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('role', function (Blueprint $table) {
            $table->id('role_id')->comment('角色ID');
            $table->string('name')->comment('名稱');
            $table->string('name_zh')->comment('名稱(中文)');
            $table->timestamps();

            $table->unique('name');
        });
    }
};

