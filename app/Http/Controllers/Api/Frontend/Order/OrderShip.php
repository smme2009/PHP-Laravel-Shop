<?php

namespace App\Http\Controllers\Api\Frontend\Order;

use App\Http\Controllers\Controller;

use App\Http\Service\Frontend\Order\OrderShip as SrcOrderShip;

/**
 * 訂單運送方式
 */
class OrderShip extends Controller
{
    public function __construct(
        private SrcOrderShip $srcOrderShip,
    ) {
    }

    /**
     * 取得訂單運送方式列表
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderShipList()
    {
        $orderShipList = $this->srcOrderShip
            ->getOrderShipList();

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setData([
                'orderShipList' => $orderShipList,
            ])
            ->setMessage('成功取得訂單運送方式列表')
            ->get();

        return $response;
    }
}