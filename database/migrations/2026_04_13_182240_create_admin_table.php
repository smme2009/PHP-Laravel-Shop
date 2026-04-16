<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tableName = 'admin'; // 資料表名稱

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->id('admin_id')->comment('管理員ID');
            $table->string('account')->unique()->comment('帳號');
            $table->string('password')->comment('密碼');
            $table->string('name')->comment('名稱');
            $table->unsignedTinyInteger('status')->comment('狀態');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
