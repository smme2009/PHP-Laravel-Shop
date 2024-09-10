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
        Schema::create('banner', function (Blueprint $table) {
            $table->id('banner_id')->comment('橫幅ID');
            $table->bigInteger('photo_fid')->unsigned()->comment('橫幅圖片ID');
            $table->string('name')->comment('名稱');
            $table->text('url')->nullable()->comment('網址');
            $table->dateTime('start_at')->nullable()->comment('上架時間');
            $table->dateTime('end_at')->nullable()->comment('下架時間');
            $table->tinyInteger('sort')->unsigned()->comment('排序');
            $table->boolean('status')->unsigned()->comment('狀態(0:關閉，1:開啟)');
            $table->timestamps();
            $table->softDeletes();

            $table->index('photo_fid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner');
    }
};
