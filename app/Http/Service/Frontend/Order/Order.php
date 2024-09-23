<?php

namespace App\Http\Service\Frontend\Order;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Service\Service;

use App\Http\Repository\Frontend\Order\Order as RepoOrder;
use App\Http\Repository\Frontend\Order\OrderShip as RepoOrderShip;
use App\Http\Repository\Frontend\Cart\Cart as RepoCart;
use App\Http\Repository\Frontend\Product\Product as RepoProduct;

/**
 * 訂單
 */
class Order extends Service
{
    // 原始商品列表
    private Collection $cartList;

    public function __construct(
        private RepoOrder $repoOrder,
        private RepoOrderShip $repoOrderShip,
        private RepoCart $repoCart,
        private RepoProduct $repoProduct,
    ) {
    }

    /**
     * 取得訂單分頁
     * 
     * @param null|string $keyword 關鍵字
     * 
     * @return array 訂單分頁
     */
    public function getOrderPage(null|string $keyword)
    {
        $page = $this->repoOrder
            ->getOrderPage($keyword);

        $orderPage = [];
        foreach ($page as $order) {
            $createdTime = $order->created_at;
            $createdTime = is_null($createdTime) ? null : strtotime($createdTime);

            $orderPage[] = [
                'code' => $order->code,
                'createdTime' => $createdTime,
                'orderShipName' => $order->orderShip->name,
                'orderPaymentName' => $order->orderPayment->name,
                'orderStatusId' => $order->order_status_id,
                'orderShipPrice' => $order->order_ship_price,
                'orderProductTotal' => $order->order_product_total,
                'orderTotal' => $order->order_total,
                'orderProductList' => $this->getOrderProductList($order->orderProduct),
            ];
        }

        return $orderPage;
    }

    /**
     * 驗證資料
     * 
     * @param array $orderData 訂單資料
     * 
     * @return \App\Tool\Validation\Result
     */
    public function validateData(array $orderData)
    {
        // 驗證資料
        $data = [
            'address' => $orderData['address'],
            'orderShipId' => $orderData['orderShipId'],
            'orderPaymentId' => $orderData['orderPaymentId'],
        ];

        // 驗證規則
        $rule = [
            'address' => ['required', 'string'],
            'orderShipId' => ['required', 'integer', 'exists:order_ship,order_ship_id'],
            'orderPaymentId' => ['required', 'integer', 'exists:order_payment,order_payment_id'],
        ];

        $result = $this->toolValidation()
            ->validateData($data, $rule);

        return $result;
    }

    /**
     * 新增完整訂單
     * 
     * @param array $orderData 訂單資料
     * 
     * @return bool 是否新增成功
     */
    public function addFullOrder(array $orderData)
    {
        $isSet = $this->setCartList($orderData['cartIdList']);

        if (!$isSet) {
            return false;
        }

        $isAdd = $this->addOrder($orderData);

        if (!$isAdd) {
            return false;
        }

        $isAdd = $this->addOrderProduct();

        return $isAdd;
    }

    /**
     * 取得訂單商品列表
     * 
     * @param object $orderProductList
     * 
     * @return array 訂單商品列表
     */
    private function getOrderProductList(object $orderProductList)
    {
        $list = [];
        foreach ($orderProductList as $orderProduct) {
            $photoUrl = $this->toolFile()
                ->getFileUrl($orderProduct->productPhoto->path);

            $list[] = [
                'name' => $orderProduct->name,
                'photoUrl' => $photoUrl,
                'quantity' => $orderProduct->quantity,
                'price' => $orderProduct->price,
                'total' => $orderProduct->total,
            ];
        }

        return $list;
    }

    /**
     * 新增訂單
     * 
     * @param array $orderData 訂單資料
     * 
     * @return bool
     */
    private function addOrder(array $orderData)
    {
        $orderShipPrice = $this->getOrderShipPrice($orderData['orderShipId']);
        $orderProductTotal = $this->getOrderProductTotal();

        $orderData['code'] = $this->getOrderCode();
        $orderData['orderShipPrice'] = $orderShipPrice;
        $orderData['orderProductTotal'] = $orderProductTotal;
        $orderData['orderTotal'] = $orderShipPrice + $orderProductTotal;

        $isAdd = $this->repoOrder->addOrder($orderData);

        return $isAdd;
    }

    /**
     * 新增訂單商品
     * 
     * @return bool 是否新增成功
     */
    private function addOrderProduct()
    {
        $orderProductList = [];
        foreach ($this->cartList as $cart) {
            $orderProductList[] = [
                'productId' => $cart->product_id,
                'quantity' => $cart->quantity,
                'originalProduct' => $cart->originalProduct,
            ];
        }

        // 新增訂單商品
        $isAdd = $this->repoOrder
            ->addOrderProduct($orderProductList);

        if (!$isAdd) {
            return false;
        }

        // 扣除商品數量
        $isReduce = $this->repoProduct
            ->reduceProduct($orderProductList);

        if (!$isReduce) {
            return false;
        }

        $cartIdList = $this->cartList->pluck('cart_id')->all();

        $isDelete = $this->repoCart
            ->deleteCartProduct($cartIdList);

        return $isDelete;
    }

    /**
     * 設定購物車列表
     * 
     * @param array $cartIdList 購物車ID列表
     * 
     * @return bool 是否設定成功
     */
    private function setCartList(array $cartIdList)
    {
        $cartList = $this->repoCart
            ->getCartProductList($cartIdList);

        $productIdList = $cartList
            ->pluck('product_id')
            ->all();

        // 批次取得商品並鎖定
        $productList = $this->repoProduct
            ->getProduct($productIdList, true);

        foreach ($cartList as $key => $cart) {
            $originalProduct = $productList
                ->where('product_id', $cart->product_id)
                ->first();

            // 檢查商品狀態
            if (!$originalProduct || !$originalProduct->status) {
                return false;
            }

            // 檢查商品數量
            if ($cart->quantity > $originalProduct->quantity) {
                return false;
            }

            $cartList[$key]->originalProduct = $originalProduct;
        }

        $this->cartList = $cartList;

        return true;
    }

    /**
     * 取的訂單編號
     * 
     * @return string 訂單編號
     */
    private function getOrderCode()
    {
        $Ymd = Carbon::now()->format('Ymd');

        $lastOrderCode = $this->repoOrder
            ->getLastOrderCode($Ymd);

        $num = ($lastOrderCode !== '') ? substr($lastOrderCode, -6) : '000000';
        $num++;
        $num = str_pad($num, 6, '0', STR_PAD_LEFT);

        $orderCode = 'cd' . $Ymd . $num;

        return $orderCode;
    }

    /**
     * 取得訂單運費
     * 
     * @param int $orderShipId 訂單運送方式ID
     * 
     * @return int 運費
     */
    private function getOrderShipPrice(int $orderShipId)
    {
        $orderShipList = $this->repoOrderShip
            ->getOrderShipList();

        $orderShipPrice = $orderShipList
            ->where('order_ship_id', $orderShipId)
            ->first()
            ->price;

        return $orderShipPrice;
    }

    /**
     * 取得訂單商品總額
     * 
     * @return int 訂單商品總額
     */
    private function getOrderProductTotal()
    {
        $total = 0;
        foreach ($this->cartList as $cart) {
            $total += $cart->quantity * $cart->originalProduct->price;
        }

        return $total;
    }
}
