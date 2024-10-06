<?php

namespace App\Http\Repository\Shop\Product;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Product as ModelProduct;

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
     * @return LengthAwarePaginator 商品分頁資料
     */
    public function getProductPage(array $searchData): LengthAwarePaginator
    {
        $productPage = $this->getBasicQuery()
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
     * @param int|array $productId 商品ID
     * @param bool $lock 是否鎖表
     * 
     * @return null|ModelProduct|Collection
     */
    public function getProduct(int|array $productId, bool $lock = false): null|ModelProduct|Collection
    {
        $query = $this->getBasicQuery();

        $function = is_int($productId) ? 'where' : 'whereIn';
        $query->{$function}('product_id', $productId);

        if ($lock) {
            $query->lockForUpdate();
        }

        $function = is_int($productId) ? 'first' : 'get';
        $product = $query->{$function}();

        return $product;
    }

    /**
     * 減少商品(批次)
     * 
     * @param array $orderProductList 訂單商品列表
     * 
     * @return bool 是否成功
     */
    public function reduceProduct(array $orderProductList)
    {
        $orderProductIdList = [];
        $quantityQuery = '';
        foreach ($orderProductList as $orderProduct) {
            $orderProductIdList[] = $orderProduct['productId'];
            $quantityQuery .= 'WHEN product_id = ' . $orderProduct['productId'] . ' ';
            $quantityQuery .= 'THEN quantity - ' . $orderProduct['quantity'] . ' ';
        }

        $quantityQuery = 'CASE ' . $quantityQuery . 'END';
        $quantityQuery = DB::raw($quantityQuery);
        $updatedAt = Carbon::now()->format('Y-m-d H:i:s');

        $isEdit = $this->product
            ->whereIn('product_id', $orderProductIdList)
            ->update([
                'quantity' => $quantityQuery,
                'updated_at' => $updatedAt,
            ]);

        return $isEdit;
    }

    /**
     * 取得基本Query
     * 
     * @return ModelProduct
     */
    private function getBasicQuery()
    {
        $nowDate = Carbon::now()->format('Y-m-d H:i:s');

        $query = $this->product
            ->where(function ($query) use ($nowDate) {
                $query->where('start_at', '<=', $nowDate)
                    ->orWhereNull('start_at');
            })
            ->where(function ($query) use ($nowDate) {
                $query->where('end_at', '>=', $nowDate)
                    ->orWhereNull('end_at');
            })
            ->where('status', true);

        return $query;
    }
}
