<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 購物車
 */
class Cart extends Model
{
    use HasFactory;

    // 資料表名稱
    protected $table = 'cart';

    // 主鍵名稱
    protected $primaryKey = 'cart_id';

    /**
     * 取得商品
     * 
     * @return BelongsTo 商品
     */
    public function product(): BelongsTo
    {
        $key = 'product_id';

        $product = $this->belongsTo(Product::class, $key, $key);

        return $product;
    }
}
