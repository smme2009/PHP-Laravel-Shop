<?php

namespace App\Http\Service\Shop\Product;

use App\Http\Service\Service;

use App\Http\Repository\Shop\Product\ProductType as RepoProductType;

/**
 * 商品分類
 */
class ProductType extends Service
{
    public function __construct(
        private RepoProductType $repoProductType,
    ) {
    }

    /**  
     * 取得商品分類列表
     * 
     * @return array
     */
    public function getProductTypeList()
    {
        $list = $this->repoProductType->getProductTypeList();

        $productTypeList = [];
        foreach ($list as $productType) {
            $productTypeList[] = $this->setProductType($productType);
        }

        return $productTypeList;
    }

    /**
     * 設定商品分類資料結構
     * 
     * @param mixed $productType 商品分類資料
     * 
     * @return array 商品分類資料結構
     */
    private function setProductType(mixed $productType)
    {
        $productType = [
            'productTypeId' => $productType->product_type_id,
            'name' => $productType->name,
        ];

        return $productType;
    }
}
