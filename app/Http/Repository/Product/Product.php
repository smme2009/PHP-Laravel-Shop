<?php

namespace App\Http\Repository\Product;

use App\Models\Product as ModelProduct;

/**
 * 商品
 */
class Product
{
    /**
     * 取得商品Collection
     * 
     * @return mixed
     */
    public function getProductColl(): mixed
    {
        $product = ModelProduct::paginate();

        return $product;
    }
}
