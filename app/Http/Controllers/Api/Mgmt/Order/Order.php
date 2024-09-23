<?php

namespace App\Http\Controllers\Api\Mgmt\Order;

use App\Http\Controllers\Controller;

use App\Http\Service\Mgmt\Order\Order as SrcOrder;

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

    /**
     * 取得訂單
     * 
     * @param int $orderId 訂單ID
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrder(int $orderId)
    {
        $order = $this->srcOrder
            ->getOrder($orderId);

        if (!$order) {
            $response = $this->toolResponseJson()
                ->setHttpCode(404)
                ->setMessage('取得訂單失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setMessage('成功取得訂單')
            ->setData([
                'order' => $order,
            ])
            ->get();

        return $response;
    }
}
