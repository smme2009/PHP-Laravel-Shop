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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 商品分頁資料
     */
    public function getProductPage()
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
            ->orderByDesc('created_at')
            ->paginate();

        return $productPage;
    }
}
