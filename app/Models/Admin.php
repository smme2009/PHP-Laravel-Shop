<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 管理員
 */
class Admin extends Model
{
    use HasFactory;

    /**
     * 資料表名稱
     */
    protected $table = 'admin';

    /**
     * 主鍵
     */
    protected $primaryKey = 'admin_id';

    /**
     * 隱藏的欄位
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * 欄位轉換
     */
    protected $casts = [
        'password' => 'hashed',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];
}