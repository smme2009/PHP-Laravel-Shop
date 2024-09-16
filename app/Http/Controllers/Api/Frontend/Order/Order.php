<?php

namespace App\Http\Controllers\Api\Frontend\Order;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Http\Service\Frontend\Order\Order as SrcOrder;

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
     * 新增訂單
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOrder()
    {
        $orderData = [
            'address' => request()->get('address'),
            'orderShipId' => request()->get('orderShipId'),
            'orderPaymentId' => request()->get('orderPaymentId'),
            'cartIdList' => request()->get('cartIdList'),
        ];

        $result = $this->srcOrder
            ->validateData($orderData);

        if (!$result->status) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setData($result->error)
                ->get();

            return $response;
        }

        DB::beginTransaction();

        $isAdd = $this->srcOrder
            ->addFullOrder($orderData);

        if (!$isAdd) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('新增訂單失敗')
                ->get();

            return $response;
        }

        DB::commit();

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功新增訂單')
            ->get();

        return $response;
    }
}