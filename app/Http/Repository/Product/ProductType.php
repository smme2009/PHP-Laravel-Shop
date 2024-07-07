<?php

namespace App\Http\Repository\Product;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Models\ProductType as ModelProductType;

/**
 * 商品類型
 */
class ProductType
{
    public function __construct(
        public ModelProductType $productType,
    ) {

    }

    /**
     * 取得商品類型分頁
     * 
     * @param array $searchData 搜尋資料
     * 
     * @return LengthAwarePaginator
     */
    public function getProductTypePage(array $searchData): LengthAwarePaginator
    {
        $productTypePage = $this->productType
            ->when(
                $searchData['keyword'],
                function ($query) use ($searchData) {
                    $query->where('name', 'like', '%' . $searchData['keyword'] . '%');
                }
            )
            ->orderByDesc('product_type_id')
            ->paginate();

        return $productTypePage;
    }

    /**
     * 設定商品類型
     * 
     * @param int $productTypeId 商品類型ID
     * 
     * @return bool 是否設定成功
     */
    public function setProductType(int $productTypeId): bool
    {
        $productType = $this->productType->find($productTypeId);

        if (!$productType) {
            return false;
        }

        $this->productType = $productType;

        return true;
    }

    /**
     * 新增商品類型
     * 
     * @param array $productTypeData 商品類型資料
     * 
     * @return false|int 商品類型ID
     */
    public function addProductType(array $productTypeData): false|int
    {
        $isSave = $this->saveModel($productTypeData);

        if (!$isSave) {
            return false;
        }

        $product_type_id = $this->productType->product_type_id;

        return $product_type_id;
    }

    /**
     * 編輯商品類型
     * 
     * @param array $productTypeData 商品類型資料
     * 
     * @return bool 是否編輯成功
     */
    public function editProductType(array $productTypeData): bool
    {
        $isSave = $this->saveModel($productTypeData);

        return $isSave;
    }

    /**
     * 刪除商品類型
     * 
     * @return bool 是否刪除成功
     */
    public function deleteProductType(): bool
    {
        $isDelete = $this->productType->delete();

        return $isDelete;
    }

    /**
     * 編輯商品類型狀態
     * 
     * @param bool $status 狀態
     * 
     * @return bool 是否編輯成功
     */
    public function editProductTypeStatus(bool $status): bool
    {
        $this->productType->status = $status;

        $isEdit = $this->productType->save();

        return $isEdit;
    }

    /**
     * 儲存商品類型Model
     * 
     * @param array $productTypeData 商品類型資料
     * 
     * @return bool 是否儲存成功
     */
    public function saveModel(array $productTypeData): bool
    {
        $this->productType->name = $productTypeData['name'];
        $this->productType->status = $productTypeData['status'];

        $isSave = $this->productType->save();

        return $isSave;
    }
}
