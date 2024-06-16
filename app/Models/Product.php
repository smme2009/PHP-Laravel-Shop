<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 商品
 */
class Product extends Model
{
    use HasFactory;

    // 軟刪除
    use SoftDeletes;

    // 資料表名稱
    protected $table = 'product';

    // 主鍵名稱
    protected $primaryKey = 'product_id';

    /**
     * 取得商品類型
     * 
     * @return BelongsTo 商品類型
     */
    public function productType(): BelongsTo
    {
        $key = 'product_type_id';

        $productType = $this->belongsTo(ProductType::class, $key, $key);

        return $productType;
    }
}
