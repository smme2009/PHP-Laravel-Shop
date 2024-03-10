<?php

namespace App\Http\Controllers\Api\Backend\Product;

use App\Http\Controllers\Controller;

use App\Http\Service\Product\Product as SrcProduct;

use App\Tool\Response\Json as ToolResponseJson;

/**
 * 商品
 */
class Product extends Controller
{
    public function __construct(
        private SrcProduct $srcProduct,
    ) {
    }

    /**
     * 取得商品列表
     * 
     * @return mixed
     */
    public function getProductPage(): mixed
    {
        $productPage = $this->srcProduct->getProductPage();

        $response = ToolResponseJson::init()
            ->setMessage('成功取得商品分頁資料')
            ->setData([
                'productPage' => $productPage,
            ])
            ->get();

        return $response;
    }

    /**
     * 取得商品
     * 
     * @param int $productId
     * 
     * @return mixed
     */
    public function getProduct(int $productId): mixed
    {
        $product = $this->srcProduct->getProduct($productId);

        $response = ToolResponseJson::init()
            ->setMessage('成功取得商品資料')
            ->setData([
                'product' => $product,
            ])
            ->get();

        return $response;
    }
}
