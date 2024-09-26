<?php

namespace App\Http\Service\Shop\Product;

use App\Http\Service\Service;
use App\Http\Repository\Shop\Product\Product as RepoProduct;
use App\Models\Product as ModelProduct;

/**
 * 商品
 */
class Product extends Service
{
    public function __construct(
        private RepoProduct $repoProduct,
    ) {
    }

    /**  
     * 取得商品分頁
     * 
     * @param array $searchData 搜尋資料
     * 
     * @return array 商品分頁
     */
    public function getProductPage(array $searchData): array
    {
        $page = $this->repoProduct
            ->getProductPage($searchData);

        $data = [];
        foreach ($page as $product) {
            $data[] = $this->setProduct($product);
        }

        $productPage = [
            'data' => $data,
            'total' => $page->total(),
        ];

        return $productPage;
    }

    /**
     * 取得商品
     * 
     * @param int $productId 商品ID
     * 
     * @return bool|array 商品
     */
    public function getProduct(int $productId): bool|array
    {
        $productData = $this->repoProduct
            ->getProduct($productId);

        if ($productData === false) {
            return false;
        }

        $product = $this->setProduct($productData);

        return $product;
    }

    /**
     * 設定商品資料結構
     * 
     * @param ModelProduct $product 商品資料
     * 
     * @return array 商品資料結構
     */
    private function setProduct(ModelProduct $product): array
    {
        $photoUrl = $this->toolFile()
            ->getFileUrl($product->productPhoto->path);

        $product = [
            'productId' => $product->product_id,
            'photoUrl' => $photoUrl,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'description' => $product->description,
            'pageHtml' => $product->page_html,
        ];

        return $product;
    }
}
