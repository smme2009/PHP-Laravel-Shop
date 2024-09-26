<?php

namespace App\Http\Repository\Shop\Order;

use Illuminate\Database\Eloquent\Collection;
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
     * @return Collection 訂單運送方式列表
     */
    public function getOrderShipList(): Collection
    {
        $orderShipList = $this->orderShip
            ->where('status', 1)
            ->get();

        return $orderShipList;
    }
}
