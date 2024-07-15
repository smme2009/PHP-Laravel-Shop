<?php

namespace App\Http\Service\Product;

use App\Http\Service\Service;

use App\Http\Repository\Product\Product as RepoProduct;
use App\Http\Repository\Product\ProductStock as RepoProductStock;

/**
 * 商品庫存單
 */
class ProductStock extends Service
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
     * @return \App\Tool\Validation\Result 驗證結果
     */
    public function validateData(array $productStockData)
    {
        // 驗證規則
        $rule = [
            'productStockTypeId' => ['required', 'integer', 'exists:product_stock_type,product_stock_type_id'],
            'quantity' => ['required', 'integer'],
        ];

        $result = $this->toolValidation()->validateData($productStockData, $rule);

        return $result;
    }

    /**
     * 新增商品庫存單
     * 
     * @param int $productId 商品ID
     * @param array $productStockData 商品庫存單資料
     * 
     * @return false|int 商品庫存單ID
     */
    public function addProductStock(int $productId, array $productStockData): false|int
    {
        $isSet = $this->repoProduct->setProduct($productId, true);

        if (!$isSet) {
            return false;
        }

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
            ->editProductQuantity($editType, $productStockData['quantity']);

        if (!$isEdit) {
            return false;
        }

        return $productStockId;
    }
}
