<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// 角色
class Role extends Model
{
    use HasFactory;

    // 資料表名稱
    protected $table = 'role';

    // 主鍵名稱
    protected $primaryKey = 'role_id';
}
