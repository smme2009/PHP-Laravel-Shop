<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 會員地址
 */
class MemberAddress extends Model
{
    use HasFactory;

    // 資料表名稱
    protected $table = 'member_address';

    // 主鍵名稱
    protected $primaryKey = 'member_address_id';
}
