<?php

namespace App\Http\Service\Shop\Order;

use App\Http\Service\Service;
use App\Http\Repository\Shop\Order\OrderShip as RepoOrderShip;

/**
 * 訂單運送方式
 */
class OrderShip extends Service
{
    public function __construct(
        private RepoOrderShip $repoOrderShip,
    ) {
    }

    /**
     * 取得訂單運送方式列表
     * 
     * @return array 運送方式
     */
    public function getOrderShipList(): array
    {
        $list = $this->repoOrderShip
            ->getOrderShipList();

        $orderShipList = [];
        foreach ($list as $orderShip) {
            $orderShipList[] = [
                'orderShipId' => $orderShip->order_ship_id,
                'name' => $orderShip->name,
                'price' => $orderShip->price,
            ];
        }

        return $orderShipList;
    }
}
