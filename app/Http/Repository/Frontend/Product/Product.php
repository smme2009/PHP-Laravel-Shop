<?php

namespace App\Http\Repository\Frontend\Product;

use App\Models\Product as ModelProduct;
use Carbon\Carbon;

/**
 * 商品
 */
class Product
{
    public function __construct(
        public ModelProduct $product,
    ) {

    }

    /**
     * 取得商品分頁
     * 
     * @param array $searchData 搜尋資料
     * 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 商品分頁資料
     */
    public function getProductPage(array $searchData)
    {
        $carbon = new Carbon();
        $nowDate = $carbon->toDateTimeString();

        $productPage = $this->product
            ->where(function ($query) use ($nowDate) {
                $query->where('start_at', '<=', $nowDate)
                    ->orWhereNull('start_at');
            })
            ->where(function ($query) use ($nowDate) {
                $query->where('end_at', '>=', $nowDate)
                    ->orWhereNull('end_at');
            })
            ->where('status', true)
            ->when($searchData['productTypeId'], function ($query) use ($searchData) {
                $query->where('product_type_id', $searchData['productTypeId']);
            })
            ->when($searchData['keyword'], function ($query) use ($searchData) {
                $query->where('name', 'like', '%' . $searchData['keyword'] . '%');
            })
            ->orderByDesc('created_at')
            ->paginate();

        return $productPage;
    }

    /**
     * 取得商品
     * 
     * @param int $productId 商品ID
     * 
     * @return null|ModelProduct
     */
    public function getProduct(int $productId)
    {
        $carbon = new Carbon();
        $nowDate = $carbon->toDateTimeString();

        $product = $this->product
            ->where('product_id', $productId)
            ->where(function ($query) use ($nowDate) {
                $query->where('start_at', '<=', $nowDate)
                    ->orWhereNull('start_at');
            })
            ->where(function ($query) use ($nowDate) {
                $query->where('end_at', '>=', $nowDate)
                    ->orWhereNull('end_at');
            })
            ->where('status', true)
            ->first();

        return $product;
    }
}
