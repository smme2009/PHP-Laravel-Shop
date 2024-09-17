<?php

namespace App\Http\Service\Frontend\Order;

use Carbon\Carbon;
use App\Http\Service\Service;

use App\Http\Repository\Frontend\Order\Order as RepoOrder;
use App\Http\Repository\Frontend\Cart\Cart as RepoCart;
use App\Http\Repository\Frontend\Product\Product as RepoProduct;

/**
 * 訂單
 */
class Order extends Service
{
    public function __construct(
        private RepoOrder $repoOrder,
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
                'orderShip' => $order->orderShip->name,
                'orderPayment' => $order->orderPayment->name,
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
        $isAdd = $this->addOrder($orderData);

        if (!$isAdd) {
            return false;
        }

        $isAdd = $this->addOrderProduct($orderData['cartIdList']);

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
        $orderData['code'] = $this->getOrderCode();

        $isAdd = $this->repoOrder->addOrder($orderData);

        return $isAdd;
    }

    /**
     * 新增訂單商品
     * 
     * @param array $cartIdList 購物車ID列表
     * 
     * @return bool 是否新增成功
     */
    private function addOrderProduct(array $cartIdList)
    {
        $cartList = $this->repoCart
            ->getCartProductList($cartIdList);

        // 取得原始商品
        $originalProductList = $this->getOriginalProductList($cartList);

        $orderProductList = [];
        foreach ($cartList as $cart) {
            $originalProduct = $originalProductList
                ->where('product_id', $cart->product_id)
                ->first();

            $result = $this->checkCartProduct($cart, $originalProduct);

            if (!$result) {
                return false;
            }

            $orderProductList[] = [
                'productId' => $cart['product_id'],
                'quantity' => $cart['quantity'],
                'originalProduct' => $originalProduct,
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

        $isDelete = $this->repoCart
            ->deleteCartProduct($cartIdList);

        return $isDelete;
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
     * 取得原始商品列表
     * 
     * @param object $cartList 購物車列表
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getOriginalProductList(object $cartList)
    {
        $productIdList = $cartList
            ->pluck('product_id')
            ->all();

        // 批次取得商品並鎖定
        $productList = $this->repoProduct
            ->getProduct($productIdList, true);

        return $productList;
    }

    /**
     * 檢查購物車商品
     * 
     * @param object $cartProduct 購物車商品
     * @param null|object $originalProduct 原始商品
     * 
     * @return bool 檢查結果
     */
    private function checkCartProduct(object $cartProduct, null|object $originalProduct)
    {
        if (!$originalProduct || !$originalProduct->status) {
            return false;
        }

        if ($cartProduct->quantity > $originalProduct->quantity) {
            return false;
        }

        return true;
    }
}
