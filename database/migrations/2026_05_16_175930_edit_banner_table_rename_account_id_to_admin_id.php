<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('banner', function (Blueprint $table) {
            $table->renameColumn('account_id', 'admin_id');
            $table->renameIndex('banner_account_id_index', 'banner_admin_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('banner', function (Blueprint $table) {
            $table->renameColumn('admin_id', 'account_id');
            $table->renameIndex('banner_admin_id_index', 'banner_account_id_index');
        });
    }
};