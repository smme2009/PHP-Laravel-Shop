<?php

namespace App\Http\Controllers\Api\Frontend\Product;

use App\Http\Controllers\Controller;

use App\Http\Service\Frontend\Product\Product as SrcProduct;

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
     * 取得商品分頁
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductPage()
    {
        $searchData = [
            'productTypeId' => request()->get('productTypeId'),
        ];

        $productPage = $this->srcProduct->getProductPage($searchData);

        $response = $this->toolResponseJson()
            ->setMessage('成功取得商品分頁資料')
            ->setData([
                'productPage' => $productPage,
            ])
            ->get();

        return $response;
    }
}
