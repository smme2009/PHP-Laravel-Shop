<?php

namespace App\Http\Controllers\Api\Backend\Product;

use App\Http\Controllers\Controller;

use App\Http\Service\Product\ProductStockType as SrcProductStockType;

/**
 * 商品庫存單類型
 */
class ProductStockType extends Controller
{
    public function __construct(
        private SrcProductStockType $srcProductStockType,
    ) {
    }

    /**
     * 取得商品庫存單類型列表
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductStockTypeList()
    {
        $productStockTypeList = $this->srcProductStockType
            ->getProductStockTypeList();

        $response = $this->toolResponseJson()
            ->setMessage(['成功取得商品庫存單類型列表'])
            ->setData([
                'productStockTypeList' => $productStockTypeList
            ])
            ->get();

        return $response;
    }
}
