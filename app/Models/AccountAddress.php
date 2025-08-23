<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * 帳號地址
 */
class AccountAddress extends Model
{
    use HasFactory;

    // 軟刪除
    use SoftDeletes;

    // 資料表名稱
    protected $table = 'account_address';

    // 主鍵名稱
    protected $primaryKey = 'account_address_id';
}
