<?php

namespace App\Http\Controllers\Api\Shop\Order;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Shop\Order\OrderShip as SrcOrderShip;

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
     * @return JsonResponse
     */
    public function getOrderShipList(): JsonResponse
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