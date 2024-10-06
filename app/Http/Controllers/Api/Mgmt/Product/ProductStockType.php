<?php

namespace App\Http\Controllers\Api\Mgmt\Product;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Mgmt\Product\ProductStockType as SrcProductStockType;

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
     * @return JsonResponse
     */
    public function getProductStockTypeList(): JsonResponse
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
