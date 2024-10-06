<?php

namespace App\Http\Controllers\Api\Shop\Product;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Shop\Product\Product as SrcProduct;

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
     * @return JsonResponse
     */
    public function getProductPage(): JsonResponse
    {
        $searchData = [
            'productTypeId' => request()->get('productTypeId'),
            'keyword' => request()->get('keyword'),
        ];

        $productPage = $this->srcProduct
            ->getProductPage($searchData);

        $response = $this->toolResponseJson()
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
     * @param int $productId 商品ID
     * 
     * @return JsonResponse
     */
    public function getProduct(int $productId): JsonResponse
    {
        $product = $this->srcProduct
            ->getProduct($productId);

        if ($product === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('取得商品失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setMessage('成功取得商品')
            ->setData([
                'product' => $product,
            ])
            ->get();

        return $response;
    }
}
