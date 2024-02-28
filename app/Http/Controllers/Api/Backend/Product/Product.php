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
     */
    public function getProductList()
    {
        $productList = $this->srcProduct->getProductList();

        $response = ToolResponseJson::init()
            ->setMessage('成功取得商品列表')
            ->setData([
                'productList' => $productList,
            ])
            ->get();

        return $response;
    }
}
