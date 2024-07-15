<?php

namespace App\Http\Controllers\Api\Backend\Product;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use \Illuminate\Http\JsonResponse;

use App\Http\Service\Product\ProductStock as SrcProductStock;

/**
 * 商品庫存單
 */
class ProductStock extends Controller
{
    public function __construct(
        private SrcProductStock $srcProductStock,
    ) {
    }

    /**
     * 新增商品庫存單
     * 
     * @param int $productId 商品ID
     * 
     * @return JsonResponse
     */
    public function addProductStock(int $productId): JsonResponse
    {
        $productStockData = $this->setProductStockData();

        $result = $this->srcProductStock->validateData($productStockData);

        if (!$result->status) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result->message)
                ->get();

            return $response;
        }

        DB::beginTransaction();

        $productStockId = $this->srcProductStock->addProductStock($productId, $productStockData);

        if (!$productStockId) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('新增商品庫存單失敗')
                ->get();

            return $response;
        }

        DB::commit();

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功新增商品庫存單')
            ->setData([
                'productStockId' => $productStockId,
            ])
            ->get();

        return $response;
    }

    /**
     * 設定商品庫存單資料
     * 
     * @return array
     */
    private function setProductStockData(): array
    {
        $productStockData = [
            'productStockTypeId' => request()->get('productStockTypeId'),
            'quantity' => request()->get('quantity'),
        ];

        return $productStockData;
    }
}
