<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 訂單商品
 */
class OrderProduct extends Model
{
    use HasFactory;

    // 資料表名稱
    protected $table = 'order_product';

    // 主鍵名稱
    protected $primaryKey = 'order_product_id';

    protected $fillable = [
        'order_id',
        'product_id',
        'photo_fid',
        'name',
        'quantity',
        'price',
        'total',
        'original_product',
    ];

    /**
     * 取得商品圖片
     * 
     * @return BelongsTo
     */
    public function productPhoto(): BelongsTo
    {
        $productPhoto = $this->belongsTo(File::class, 'photo_fid', 'file_id');

        return $productPhoto;
    }
}
