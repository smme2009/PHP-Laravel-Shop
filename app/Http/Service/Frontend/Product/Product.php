<?php

namespace App\Http\Service\Frontend\Product;

use App\Http\Service\Service;

use App\Http\Repository\Frontend\Product\Product as RepoProduct;

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
     * @return array
     */
    public function getProductPage(array $searchData)
    {
        $page = $this->repoProduct->getProductPage($searchData);

        $photoFidList = $page->pluck('photo_fid')->all();
        $fileInfoList = $this->toolFile()->getFileInfoList($photoFidList);

        $data = [];
        foreach ($page as $product) {
            $fileInfo = $fileInfoList[$product->photo_fid];

            $data[] = $this->setProduct($product, $fileInfo);
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
     * @return false|array
     */
    public function getProduct(int $productId)
    {
        $productData = $this->repoProduct->getProduct($productId);

        if (!$productData) {
            return false;
        }

        $fileInfo = $this->toolFile()->getFileInfo($productData->photo_fid);

        $product = $this->setProduct($productData, $fileInfo);

        return $product;
    }

    /**
     * 設定商品資料結構
     * 
     * @param mixed $product 商品資料
     * @param array $fileInfo 商品照片檔案資訊
     * 
     * @return array 商品資料結構
     */
    private function setProduct(mixed $product, array $fileInfo)
    {
        $product = [
            'productId' => $product->product_id,
            'photoUrl' => $fileInfo['url'],
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'description' => $product->description,
            'pageHtml' => $product->page_html,
        ];

        return $product;
    }
}
