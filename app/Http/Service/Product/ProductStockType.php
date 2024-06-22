<?php

namespace App\Http\Service\Product;

use App\Http\Repository\Product\ProductStockType as RepoProductStockType;

/**
 * 商品庫存單類型
 */
class ProductStockType
{
    public function __construct(
        private RepoProductStockType $repoProductStockType,
    ) {
    }

    /**
     * 取得商品庫存單類型列表
     * 
     * @return array 商品庫存單類型列表
     */
    public function getProductStockTypeList(): array
    {
        $productStockTypeColl = $this->repoProductStockType
            ->getProductStockTypeColl();

        $productStockTypeList = [];
        foreach ($productStockTypeColl as $productStockType) {
            $productStockTypeList[] = [
                'productStockTypeId' => $productStockType->product_stock_type_id,
                'name' => $productStockType->name,
            ];
        }

        return $productStockTypeList;
    }
}
