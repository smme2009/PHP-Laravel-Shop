<?php

namespace App\Http\Repository\Frontend\Order;

use App\Models\OrderStatus as ModelOrderStatus;

/**
 * 訂單狀態
 */
class OrderStatus
{
    public function __construct(
        public ModelOrderStatus $orderStatus,
    ) {

    }

    /**
     * 取得訂單狀態列表
     * 
     * @\Illuminate\Database\Eloquent\Collection
     */
    public function getOrderStatusList()
    {
        $orderStatusList = $this->orderStatus
            ->where('status', 1)
            ->get();

        return $orderStatusList;
    }
}
