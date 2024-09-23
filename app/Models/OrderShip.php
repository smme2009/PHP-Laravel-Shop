<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 訂單運送方式
 */
class OrderShip extends Model
{
    use HasFactory;

    // 資料表名稱
    protected $table = 'order_ship';

    // 主鍵名稱
    protected $primaryKey = 'order_ship_id';
}
