<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // 啟用RLS的資料表    
    private array $tables = [
        'admin',
        'banner',
        'cart',
        'failed_jobs',
        'file',
        'log_api',
        'migrations',
        'order',
        'order_payment',
        'order_product',
        'order_ship',
        'order_status',
        'password_reset_tokens',
        'personal_access_tokens',
        'product',
        'product_stock',
        'product_stock_type',
        'product_type',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            foreach ($this->tables as $table) {
                DB::statement("ALTER TABLE \"{$table}\" ENABLE ROW LEVEL SECURITY;");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            foreach ($this->tables as $table) {
                DB::statement("ALTER TABLE \"{$table}\" DISABLE ROW LEVEL SECURITY;");
            }
        }
    }
};
