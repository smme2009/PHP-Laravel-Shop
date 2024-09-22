<?php

namespace App\Http\Service\Backend\Order;

use App\Http\Service\Service;

use App\Http\Repository\Backend\Order\Order as RepoOrder;

/**
 * 訂單
 */
class Order extends Service
{
    public function __construct(
        private RepoOrder $repoOrder,
    ) {
    }

    /**  
     * 取得訂單分頁
     * 
     * @param null|string $keyword 關鍵字
     * 
     * @return array 訂單分頁資料
     */
    public function getOrderPage(null|string $keyword)
    {
        $page = $this->repoOrder->getOrderPage($keyword);

        $data = [];
        foreach ($page as $order) {
            $data[] = $this->getOrderData($order);
        }

        $orderPage = [
            'data' => $data,
            'total' => $page->total(),
        ];

        return $orderPage;
    }

    /**
     * 設定訂單資料
     * 
     * @param object $orderModel 訂單Model
     * 
     * @return array 訂單資料
     */
    private function getOrderData(object $orderModel)
    {
        $createTime = $orderModel->created_at;
        $createTime = is_null($createTime) ? null : strtotime($createTime);

        $orderData = [
            'code' => $orderModel->code,
            'address' => $orderModel->address,
            'orderShipName' => $orderModel->orderShip->name,
            'orderPaymentName' => $orderModel->orderPayment->name,
            'orderShipPrice' => $orderModel->order_ship_price,
            'orderProductTotal' => $orderModel->order_product_total,
            'orderTotal' => $orderModel->order_total,
            'orderStatusName' => $orderModel->orderStatus->name,
            'createTime' => $createTime,
        ];

        return $orderData;
    }
}
