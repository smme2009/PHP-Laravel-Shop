<?php

namespace App\Http\Repository\Product;

use App\Models\ProductStock as ModelProductStock;

/**
 * 商品庫存單類型
 */
class ProductStock
{
    /**
     * 新增商品庫存單
     * 
     * @param array $productStockData 商品庫存單資料
     * 
     * @return false|int
     */
    public function addProductStock(array $productStockData): false|int
    {
        $model = new ModelProductStock();

        $model = $this->setModel($model, $productStockData);

        $isSave = $model->save();

        if (!$isSave) {
            return false;
        }

        $productStockId = $model->product_stock_id;

        return $productStockId;
    }

    /**
     * 設定商品Model
     * 
     * @param mixed $model
     * @param array $productStockData 商品庫存單資料
     * 
     * @return ModelProductStock
     */
    private function setModel(mixed $model, array $productData): ModelProductStock
    {
        $model->product_stock_type_id = $productData['productStockTypeId'];
        $model->product_id = $productData['productId'];
        $model->quantity = $productData['quantity'];

        return $model;
    }
}
