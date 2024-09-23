<?php

namespace App\Http\Repository\Shop\Product;

use App\Models\ProductType as ModelProductType;

/**
 * 商品分類
 */
class ProductType
{
    public function __construct(
        public ModelProductType $productType,
    ) {

    }

    /**
     * 取得商品分類列表
     * 
     * @return \Illuminate\Database\Eloquent\Collection 商品分類列表資料
     */
    public function getProductTypeList()
    {
        $productTypeList = $this->productType
            ->where('status', true)
            ->orderByDesc('created_at')
            ->get();

        return $productTypeList;
    }
}
