<?php

namespace App\Http\Service\Product;

use App\Http\Repository\Product\Product as RepoProduct;

/**
 * 商品
 */
class Product
{
    public function __construct(
        private RepoProduct $repoProduct,
    ) {
    }

    /**  
     * 取得商品列表
     * 
     * @return array
     */
    public function getProductList(): array
    {
        $productColl = $this->repoProduct->getProductColl();

        $productList = [];
        foreach ($productColl as $product) {
            $productList[] = [
                'productId' => $product->product_id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'description' => $product->description,
            ];
        }

        return $productList;
    }
}
