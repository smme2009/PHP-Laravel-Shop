<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 訂單狀態
 */
class OrderStatus extends Model
{
    use HasFactory;

    // 資料表名稱
    protected $table = 'order_status';

    // 主鍵名稱
    protected $primaryKey = 'order_status_id';
}
