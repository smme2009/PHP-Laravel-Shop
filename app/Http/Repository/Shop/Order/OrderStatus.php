<?php

namespace App\Http\Repository\Shop\Order;

use Illuminate\Database\Eloquent\Collection;
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
     * @return Collection
     */
    public function getOrderStatusList(): Collection
    {
        $orderStatusList = $this->orderStatus
            ->where('status', 1)
            ->get();

        return $orderStatusList;
    }
}
