<?php

namespace App\Http\Controllers\Api\Shop\Order;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Service\Shop\Order\Order as SrcOrder;

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
     * 取得訂單分頁
     * 
     * @return JsonResponse
     */
    public function getOrderPage(): JsonResponse
    {
        $keyword = request()->get('keyword');

        $orderPage = $this->srcOrder
            ->getOrderPage($keyword);

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setData([
                'orderPage' => $orderPage,
            ])
            ->setMessage('成功取得訂單分頁')
            ->get();

        return $response;
    }

    /**
     * 新增訂單
     * 
     * @return JsonResponse
     */
    public function addOrder(): JsonResponse
    {
        $orderData = [
            'address' => request()->get('address'),
            'orderShipId' => request()->get('orderShipId'),
            'orderPaymentId' => request()->get('orderPaymentId'),
            'cartIdList' => request()->get('cartIdList'),
        ];

        $result = $this->srcOrder
            ->validateData($orderData);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setData([
                    'errorList' => $result->error,
                ])
                ->get();

            return $response;
        }

        DB::beginTransaction();

        $isAdd = $this->srcOrder
            ->addFullOrder($orderData);

        if ($isAdd === false) {
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