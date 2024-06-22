<?php

namespace App\Http\Repository\Product;

use \Illuminate\Database\Eloquent\Collection;

use App\Models\ProductStockType as ModelProductStockType;

/**
 * 商品庫存單類型
 */
class ProductStockType
{
    /**
     * 取得商品庫存類型Collection
     * 
     * @return Collection
     */
    public function getProductStockTypeColl(): Collection
    {
        $productStockType = ModelProductStockType::all();

        return $productStockType;
    }
}
