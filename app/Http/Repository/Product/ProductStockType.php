<?php

namespace App\Http\Repository\Product;

use \Illuminate\Database\Eloquent\Collection;

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
     * @return Collection 商品庫存類型Collection
     */
    public function getProductStockTypeColl(): Collection
    {
        $productStockType = $this->productStockType->all();

        return $productStockType;
    }
}
