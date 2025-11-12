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
        Schema::table('banner', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->after('banner_id')->index()->comment('帳號ID');
            $table->renameColumn('photo_fid', 'photo_file_id');
            $table->renameIndex('banner_photo_fid_index', 'banner_photo_file_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner', function (Blueprint $table) {
            $table->dropColumn('account_id');
            $table->renameColumn('photo_file_id', 'photo_fid');
            $table->renameIndex('banner_photo_file_id_index', 'banner_photo_fid_index');
        });
    }
};
