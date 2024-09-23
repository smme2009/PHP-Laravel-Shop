<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 訂單付款方式
 */
class OrderPayment extends Model
{
    use HasFactory;

    // 資料表名稱
    protected $table = 'order_payment';

    // 主鍵名稱
    protected $primaryKey = 'order_payment_id';
}
