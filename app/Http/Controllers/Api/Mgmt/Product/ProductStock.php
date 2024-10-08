<?php

namespace App\Http\Controllers\Api\Mgmt\Product;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Mgmt\Product\ProductStock as SrcProductStock;

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
     * 取得商品庫存單分頁
     * 
     * @param int $productId 商品ID
     * 
     * @return JsonResponse
     */
    public function getProductStockPage(int $productId): JsonResponse
    {
        $productStockPage = $this->srcProductStock
            ->getProductStockPage($productId);

        $response = $this->toolResponseJson()
            ->setMessage('成功取得商品庫存單分頁資料')
            ->setData([
                'productStockPage' => $productStockPage,
            ])
            ->get();

        return $response;
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

        $result = $this->srcProductStock
            ->validateData($productStockData);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setData([
                    'errorList' => $result->error
                ])
                ->get();

            return $response;
        }

        DB::beginTransaction();

        $productStockId = $this->srcProductStock
            ->addProductStock($productId, $productStockData);

        if ($productStockId === false) {
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
