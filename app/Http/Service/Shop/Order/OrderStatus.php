<?php

namespace App\Http\Service\Shop\Order;

use App\Http\Service\Service;

use App\Http\Repository\Shop\Order\OrderStatus as RepoOrderStatus;

/**
 * 訂單狀態
 */
class OrderStatus extends Service
{
    public function __construct(
        private RepoOrderStatus $repoOrderStatus,
    ) {
    }

    /**
     * 取得訂單狀態列表
     * 
     * @return array 訂單狀態
     */
    public function getOrderStatusList()
    {
        $list = $this->repoOrderStatus
            ->getOrderStatusList();

        $orderStatusList = [];
        foreach ($list as $orderStatus) {
            $orderStatusList[] = [
                'orderStatusId' => $orderStatus->order_ship_id,
                'name' => $orderStatus->name,
            ];
        }

        return $orderStatusList;
    }
}
