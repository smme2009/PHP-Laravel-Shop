<?php

namespace App\Http\Service\Mgmt\Product;

use App\Http\Service\Service;
use App\Http\Repository\Mgmt\Product\ProductStockType as RepoProductStockType;

/**
 * 商品庫存單類型
 */
class ProductStockType extends Service
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
