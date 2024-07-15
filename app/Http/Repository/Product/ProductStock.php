<?php

namespace App\Http\Repository\Product;

use App\Models\ProductStock as ModelProductStock;

/**
 * 商品庫存單類型
 */
class ProductStock
{
    public function __construct(
        public ModelProductStock $productStock,
    ) {

    }

    /**
     * 新增商品庫存單
     * 
     * @param array $productStockData 商品庫存單資料
     * 
     * @return false|int 商品庫存單ID
     */
    public function addProductStock(array $productStockData)
    {
        $isSave = $this->saveModel($productStockData);

        if (!$isSave) {
            return false;
        }

        $productStockId = $this->productStock->product_stock_id;

        return $productStockId;
    }

    /**
     * 儲存商品庫存單Model
     * 
     * @param array $productStockData 商品庫存單資料
     * 
     * @return bool 是否儲存
     */
    private function saveModel(array $productData)
    {
        $this->productStock->product_stock_type_id = $productData['productStockTypeId'];
        $this->productStock->product_id = $productData['productId'];
        $this->productStock->quantity = $productData['quantity'];

        $isSave = $this->productStock->save();

        return $isSave;
    }
}
