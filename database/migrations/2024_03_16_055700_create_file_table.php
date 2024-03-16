<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('file', function (Blueprint $table) {
            $table->id('file_id')->comment('檔案ID');
            $table->string('name')->comment('檔案名稱');
            $table->string('extension')->comment('副檔名');
            $table->string('type')->comment('檔案類型');
            $table->integer('size')->comment('檔案大小');
            $table->text('path')->comment('檔案路徑');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file');
    }
};
