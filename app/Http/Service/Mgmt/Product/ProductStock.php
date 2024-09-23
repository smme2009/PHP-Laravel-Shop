<?php

namespace App\Http\Service\Mgmt\Product;

use App\Http\Service\Service;

use App\Http\Repository\Mgmt\Product\Product as RepoProduct;
use App\Http\Repository\Mgmt\Product\ProductStock as RepoProductStock;

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
     * 取得商品庫存單分頁
     * 
     * @param int $productId
     * 
     * @return array 商品庫存單分頁
     */
    public function getProductStockPage(int $productId)
    {
        $page = $this->repoProductStock->getProductStockPage($productId);

        $data = [];
        foreach ($page as $productStock) {
            $data[] = $this->setProductStock($productStock);
        }

        $productStockPage = [
            'data' => $data,
            'total' => $page->total(),
        ];

        return $productStockPage;
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
    public function addProductStock(int $productId, array $productStockData)
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

    /**
     * 設定商品庫存單資料結構
     * 
     * @param mixed $productStock 商品庫存單資料
     * 
     * @return array 商品庫存單資料結構
     */
    private function setProductStock(mixed $productStock)
    {
        $createTime = $productStock->created_at;
        $createTime = is_null($createTime) ? null : strtotime($createTime);

        $productStock = [
            'productStockId' => $productStock->product_stock_id,
            'productStockTypeId' => $productStock->product_stock_type_id,
            'productStockTypeName' => $productStock->productStockType->name,
            'quantity' => $productStock->quantity,
            'createTime' => $createTime,
        ];

        return $productStock;
    }
}
