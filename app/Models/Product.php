<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 商品
 */
class Product extends Model
{
    use HasFactory;

    // 資料表名稱
    protected $table = 'product';

    // 主鍵名稱
    protected $primaryKey = 'product_id';
}
