<?php

namespace App\Http\Repository\Backend\Order;

use App\Models\Order as ModelOrder;

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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 訂單分頁資料
     */
    public function getOrderPage(null|string $keyword)
    {
        $orderPage = $this->order
            ->when(
                $keyword,
                function ($query) use ($keyword) {
                    $query->where('code', 'like', '%' . $keyword . '%');
                }
            )
            ->orderByDesc('created_at')
            ->paginate();

        return $orderPage;
    }

    /**
     * 設定訂單
     * 
     * @param int $orderId 訂單ID
     * 
     * @return bool 是否設定成功
     */
    public function setOrder(int $orderId)
    {
        $order = $this->order->find($orderId);

        if (!$order) {
            return false;
        }

        $this->order = $order;

        return true;
    }
}
