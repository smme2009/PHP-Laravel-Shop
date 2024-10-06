<?php

namespace App\Http\Repository\Shop\Order;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Order as ModelOrder;
use App\Models\OrderProduct as ModelOrderProduct;

/**
 * 訂單
 */
class Order
{
    public function __construct(
        public ModelOrder $order,
    ) {

    }

    /**
     * 取得訂單分頁
     * 
     * @param null|string $keyword 關鍵字
     * 
     * @return LengthAwarePaginator 訂單分頁
     */
    public function getOrderPage(null|string $keyword): LengthAwarePaginator
    {
        $memberId = auth('member')
            ->user()
            ->member_id;

        $orderPage = $this->order
            ->where('member_id', $memberId)
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('code', 'like', '%' . $keyword . '%');
            })
            ->orderByDesc('code')
            ->paginate();

        return $orderPage;
    }

    /**
     * 取得最後的訂單編號
     * 
     * @param string $Ymd 年月日
     * 
     * @return string 訂單編號
     */
    public function getLastOrderCode(string $Ymd): string
    {
        $lastOrderCode = $this->order
            ->where('code', 'REGEXP', '^cd' . $Ymd . '.{6}$')
            ->orderByDesc('code')
            ->lockForUpdate()
            ->first()
            ->code ?? '';

        return $lastOrderCode;
    }

    /**
     * 新增訂單
     * 
     * @param array $orderData 訂單資料
     * 
     * @return bool|int
     */
    public function addOrder(array $orderData): bool|int
    {
        $this->order->member_id = auth('member')->user()->member_id;
        $this->order->code = $orderData['code'];
        $this->order->address = $orderData['address'];
        $this->order->order_ship_id = $orderData['orderShipId'];
        $this->order->order_payment_id = $orderData['orderPaymentId'];
        $this->order->order_ship_price = $orderData['orderShipPrice'];
        $this->order->order_product_total = $orderData['orderProductTotal'];
        $this->order->order_total = $orderData['orderTotal'];
        $this->order->order_status_id = 1;
        $isSave = $this->order->save();

        if ($isSave === false) {
            return false;
        }

        $orderId = $this->order->order_id;

        return $orderId;
    }

    /**
     * 新增訂單商品
     * 
     * @param array $orderProductList 訂單商品列表
     * 
     * @return bool 是否新增成功
     */
    public function addOrderProduct(array $orderProductList): bool
    {
        $product = [];
        foreach ($orderProductList as $orderProduct) {
            $originalProduct = $orderProduct['originalProduct'];

            $product[] = new ModelOrderProduct([
                'order_id' => $this->order->order_id,
                'product_id' => $orderProduct['productId'],
                'photo_fid' => $originalProduct->photo_fid,
                'name' => $originalProduct->name,
                'quantity' => $orderProduct['quantity'],
                'price' => $originalProduct->price,
                'total' => $orderProduct['quantity'] * $originalProduct->price,
                'original_product' => json_encode($originalProduct),
            ]);
        }

        $modelList = $this->order
            ->orderProduct()
            ->saveMany($product);

        $isSave = (count($modelList) === 0) ? false : true;

        return $isSave;
    }
}
