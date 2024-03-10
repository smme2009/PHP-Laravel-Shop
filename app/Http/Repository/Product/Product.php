<?php

namespace App\Http\Repository\Product;

use App\Models\Product as ModelProduct;

/**
 * 商品
 */
class Product
{
    /**
     * 取得商品分頁
     * 
     * @return mixed
     */
    public function getProductPage(): mixed
    {
        $productPage = ModelProduct::paginate();

        return $productPage;
    }

    /**
     * 取得商品
     * 
     * @param int $productId 商品ID
     * 
     * @return mixed
     */
    public function getProduct(int $productId): mixed
    {
        $product = ModelProduct::find($productId);

        return $product;
    }
}
