<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * API紀錄
 */
class LogApi extends Model
{
    use HasFactory;

    // 資料表名稱
    protected $table = 'log_api';

    // 主鍵名稱
    protected $primaryKey = 'log_api_id';

    // 可賦值欄位
    protected $fillable = [
        'uri',
        'method',
    ];
}
