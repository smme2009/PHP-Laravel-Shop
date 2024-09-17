<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 訂單
 */
class Order extends Model
{
    use HasFactory;

    // 資料表名稱
    protected $table = 'order';

    // 主鍵名稱
    protected $primaryKey = 'order_id';

    /**
     * 取得訂單商品
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany 訂單商品
     */
    public function orderProduct()
    {
        $key = 'order_id';

        $orderProduct = $this->hasMany(orderProduct::class, $key, $key);

        return $orderProduct;
    }

    /**
     * 取得訂單運送方式
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo 訂單運送方式
     */
    public function orderShip()
    {
        $key = 'order_ship_id';

        $orderShip = $this->belongsTo(OrderShip::class, $key, $key);

        return $orderShip;
    }

    /**
     * 取得訂單付款方式
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo 訂單付款方式
     */
    public function orderPayment()
    {
        $key = 'order_payment_id';

        $orderPayment = $this->belongsTo(OrderPayment::class, $key, $key);

        return $orderPayment;
    }

    /**
     * 取得訂單狀態
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo 訂單狀態
     */
    public function orderStatus()
    {
        $key = 'order_status_id';

        $orderStatus = $this->belongsTo(OrderStatus::class, $key, $key);

        return $orderStatus;
    }
}
