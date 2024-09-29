<?php

namespace App\Http\Repository\Public\Product;

use Illuminate\Database\Eloquent\Collection;
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
     * @return Collection 商品分類列表資料
     */
    public function getProductTypeList(): Collection
    {
        $productTypeList = $this->productType
            ->where('status', true)
            ->orderByDesc('created_at')
            ->get();

        return $productTypeList;
    }
}
