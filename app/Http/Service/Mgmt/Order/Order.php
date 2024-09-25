<?php

namespace App\Http\Service\Mgmt\Order;

use App\Http\Service\Service;
use App\Http\Repository\Mgmt\Order\Order as RepoOrder;
use App\Models\Order as ModelOrder;
use App\Models\OrderProduct as ModelOrderProduct;

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
    public function getOrderPage(null|string $keyword): array
    {
        $page = $this->repoOrder
            ->getOrderPage($keyword);

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
     * 取得訂單
     * 
     * @param int $orderId 訂單ID
     * 
     * @return bool|array 訂單
     */
    public function getOrder(int $orderId): bool|array
    {
        $isSet = $this->repoOrder
            ->setOrder($orderId);

        if ($isSet === false) {
            return false;
        }

        $orderModel = $this->repoOrder->order;
        $order = $this->getOrderData($orderModel);

        return $order;
    }

    /**
     * 取得訂單資料
     * 
     * @param ModelOrder $orderModel 訂單Model
     * 
     * @return array 訂單資料
     */
    private function getOrderData(ModelOrder $orderModel): array
    {
        // 新增時間
        $createTime = $orderModel->created_at;
        $createTime = is_null($createTime)
            ? null
            : strtotime($createTime);

        // 訂單商品列表
        $orderProductList = [];
        foreach ($orderModel->orderProduct as $orderProductModel) {
            $orderProductList[] = $this->getOrderProductList($orderProductModel);
        }

        $orderData = [
            'orderId' => $orderModel->order_id,
            'code' => $orderModel->code,
            'address' => $orderModel->address,
            'orderShipName' => $orderModel->orderShip->name,
            'orderPaymentName' => $orderModel->orderPayment->name,
            'orderShipPrice' => $orderModel->order_ship_price,
            'orderProductTotal' => $orderModel->order_product_total,
            'orderTotal' => $orderModel->order_total,
            'orderStatusName' => $orderModel->orderStatus->name,
            'createTime' => $createTime,
            'orderProductList' => $orderProductList,
        ];

        return $orderData;
    }

    /**
     * 取得訂單商品列表
     * 
     * @param ModelOrderProduct $orderProductModel 訂單商品Model
     * 
     * @return array 訂單商品列表
     */
    private function getOrderProductList(ModelOrderProduct $orderProductModel): array
    {
        $photoUrl = $this->toolFile()
            ->getFileUrl($orderProductModel->productPhoto->path);

        $orderProductData = [
            'photoUrl' => $photoUrl,
            'name' => $orderProductModel->name,
            'quantity' => $orderProductModel->quantity,
            'price' => $orderProductModel->price,
            'total' => $orderProductModel->total,
        ];

        return $orderProductData;
    }
}
