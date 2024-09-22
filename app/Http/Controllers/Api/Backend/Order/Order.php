<?php

namespace App\Http\Controllers\Api\Backend\Order;

use App\Http\Controllers\Controller;

use App\Http\Service\Backend\Order\Order as SrcOrder;

/**
 * 訂單
 */
class Order extends Controller
{
    public function __construct(
        private SrcOrder $srcOrder,
    ) {
    }

    /**
     * 取得訂單列表
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrderPage()
    {
        $keyword = request()->get('keyword');

        $orderPage = $this->srcOrder->getOrderPage($keyword);

        $response = $this->toolResponseJson()
            ->setMessage('成功取得訂單分頁資料')
            ->setData([
                'orderPage' => $orderPage,
            ])
            ->get();

        return $response;
    }
}
