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
     * 取得商品分頁
     * 
     * @return array
     */
    public function getProductPage(): array
    {
        $page = $this->repoProduct->getProductPage();

        $productPage = [];
        foreach ($page as $product) {
            $productPage[] = $this->setProduct($product);
        }

        return $productPage;
    }

    /**
     * 取得商品
     * 
     * @param int $productId 商品ID
     * 
     * @return array
     */
    public function getProduct(int $productId): array
    {
        $product = $this->repoProduct->getProduct($productId);
        $product = $this->setProduct($product);

        return $product;
    }

    /**
     * 設定商品資料結構
     * 
     * @param mixed $product 商品資料
     * 
     * @return array
     */
    private function setProduct(mixed $product)
    {
        $product = [
            'productId' => $product->product_id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'description' => $product->description,
        ];

        return $product;
    }
}
