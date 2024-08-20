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
        Schema::rename('user', 'admin');

        Schema::table('admin', function (Blueprint $table) {
            $table->renameColumn('user_id', 'admin_id');
            $table->renameColumn('email', 'account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('admin', 'user');

        Schema::table('user', function (Blueprint $table) {
            $table->renameColumn('admin_id', 'user_id');
            $table->renameColumn('account', 'email');
        });
    }
};
