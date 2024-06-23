<?php

namespace App\Http\Service\Product;

use App\Http\Repository\Product\Product as RepoProduct;
use App\Http\Repository\Product\ProductStock as RepoProductStock;

use App\Tool\ValidateData as ToolValidateData;

/**
 * 商品庫存單
 */
class ProductStock
{
    public function __construct(
        private RepoProduct $repoProduct,
        private RepoProductStock $repoProductStock,
    ) {
    }

    /**  
     * 驗證資料
     * 
     * @param array $productStockData 商品庫存單資料
     * 
     * @return array
     */
    public function validateData(array $productStockData): array
    {
        // 驗證規則
        $rule = [
            'productStockTypeId' => ['required', 'integer', 'exists:product_stock_type,product_stock_type_id'],
            'quantity' => ['required', 'integer'],
        ];

        $result = ToolValidateData::validateData($productStockData, $rule);

        return $result;
    }

    /**
     * 新增商品庫存單
     * 
     * @param int $productId 商品ID
     * @param array $productStockData 商品庫存單資料
     * 
     * @return int|false 商品庫存單ID
     */
    public function addProductStock(int $productId, array $productStockData): int|false
    {
        $productStockData['productId'] = $productId;

        $productStockId = $this->repoProductStock->addProductStock($productStockData);

        if (!$productStockId) {
            return false;
        }

        $editType = null;
        switch ($productStockData['productStockTypeId']) {
            case 1: // 上架單
                $editType = 1;
                break;
            case 2: // 下架單
                $editType = 0;
                break;
        }

        $isEdit = $this->repoProduct
            ->editProductQuantity($productId, $editType, $productStockData['quantity']);

        if (!$isEdit) {
            return false;
        }

        return $productStockId;
    }
}
