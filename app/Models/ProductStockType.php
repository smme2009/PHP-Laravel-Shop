<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * 商品庫存單類型
 */
class ProductStockType extends Model
{
    use HasFactory;

    // 軟刪除
    use SoftDeletes;

    // 資料表名稱
    protected $table = 'product_stock_type';

    // 主鍵名稱
    protected $primaryKey = 'product_stock_type_id';
}
