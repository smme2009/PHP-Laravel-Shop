<?php

namespace App\Http\Controllers\Api\Shop\Order;

use App\Http\Controllers\Controller;

use App\Http\Service\Shop\Order\OrderStatus as SrcOrderStatus;

/**
 * 訂單狀態
 */
class OrderStatus extends Controller
{
    public function __construct(
        private SrcOrderStatus $srcOrderStatus,
    ) {
    }

    /**
     * 取得訂單狀態列表
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderStatusList()
    {
        $orderStatusList = $this->srcOrderStatus
            ->getOrderStatusList();

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setData([
                'orderStatusList' => $orderStatusList,
            ])
            ->setMessage('成功取得訂單狀態列表')
            ->get();

        return $response;
    }
}