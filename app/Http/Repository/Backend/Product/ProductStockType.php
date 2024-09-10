<?php

namespace App\Http\Repository\Backend\Product;

use App\Models\ProductStockType as ModelProductStockType;

/**
 * 商品庫存單類型
 */
class ProductStockType
{
    public function __construct(
        public ModelProductStockType $productStockType,
    ) {

    }

    /**
     * 取得商品庫存類型Collection
     * 
     * @return \Illuminate\Database\Eloquent\Collection 商品庫存類型Collection
     */
    public function getProductStockTypeColl()
    {
        $productStockType = $this->productStockType->all();

        return $productStockType;
    }
}
