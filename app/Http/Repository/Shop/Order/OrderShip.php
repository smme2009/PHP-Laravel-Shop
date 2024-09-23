<?php

namespace App\Http\Repository\Shop\Order;

use App\Models\OrderShip as ModelOrderShip;

/**
 * 訂單運送方式
 */
class OrderShip
{
    public function __construct(
        public ModelOrderShip $orderShip,
    ) {

    }

    /**
     * 取得訂單運送方式列表
     * 
     * @\Illuminate\Database\Eloquent\Collection
     */
    public function getOrderShipList()
    {
        $orderShipList = $this->orderShip
            ->where('status', 1)
            ->get();

        return $orderShipList;
    }
}
