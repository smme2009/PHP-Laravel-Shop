<?php

namespace App\Http\Controllers\Api\Public\Product;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Public\Product\ProductType as SrcProductType;

/**
 * 商品分類
 */
class ProductType extends Controller
{
    public function __construct(
        private SrcProductType $srcProductType,
    ) {
    }

    /**
     * 取得商品分類列表
     * 
     * @return JsonResponse
     */
    public function getProductTypeList(): JsonResponse
    {
        $productTypeList = $this->srcProductType
            ->getProductTypeList();

        $response = $this->toolResponseJson()
            ->setMessage('成功取得商品分類列表資料')
            ->setData([
                'productTypeList' => $productTypeList,
            ])
            ->get();

        return $response;
    }
}
