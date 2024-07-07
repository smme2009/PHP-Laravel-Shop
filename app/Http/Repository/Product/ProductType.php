<?php

namespace App\Http\Repository\Product;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use App\Models\ProductType as ModelProductType;

/**
 * 商品類型
 */
class ProductType
{
    /**
     * 取得商品類型分頁
     * 
     * @param array $searchData 搜尋資料
     * 
     * @return LengthAwarePaginator
     */
    public function getProductTypePage(array $searchData): LengthAwarePaginator
    {
        $productTypePage = ModelProductType::when(
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
     * 取得商品類型
     * 
     * @param int $productTypeId 商品類型ID
     * 
     * @return null|ModelProductType
     */
    public function getProductType(int $productTypeId): null|ModelProductType
    {
        $productType = ModelProductType::find($productTypeId);

        return $productType;
    }

    /**
     * 新增商品類型
     * 
     * @param array $productTypeData 商品類型資料
     * 
     * @return int|false
     */
    public function addProductType(array $productTypeData): int|false
    {
        $model = new ModelProductType();

        $model = $this->setModel($model, $productTypeData);

        $isSave = $model->save();

        if (!$isSave) {
            return false;
        }

        $product_type_id = $model->product_type_id;

        return $product_type_id;
    }

    /**
     * 編輯商品類型
     * 
     * @param int $productTypeId 商品類型ID
     * @param array $productTypeData 商品類型資料
     * 
     * @return bool
     */
    public function editProductType(int $productTypeId, array $productTypeData): bool
    {
        $model = ModelProductType::lockForUpdate()
            ->find($productTypeId);

        if (!$model) {
            return false;
        }

        $model = $this->setModel($model, $productTypeData);

        $isSave = $model->save();

        return $isSave;
    }

    /**
     * 刪除商品類型
     * 
     * @param int $productTypeId 商品類型ID
     * 
     * @return bool
     */
    public function deleteProductType(int $productTypeId): bool
    {
        $model = ModelProductType::lockForUpdate()
            ->find($productTypeId);

        if (!$model) {
            return false;
        }

        $isDelete = $model->delete();

        return $isDelete;
    }

    /**
     * 編輯商品類型狀態
     * 
     * @param int $productTypeId 商品類型ID
     * @param bool $status 狀態
     * 
     * @return bool 是否編輯成功
     */
    public function editProductTypeStatus(int $productTypeId, bool $status): bool
    {
        $model = ModelProductType::lockForUpdate()
            ->find($productTypeId);

        if (!$model) {
            return false;
        }

        $model->status = $status;

        $isEdit = $model->save();

        return $isEdit;
    }

    /**
     * 設定商品類型Model
     * 
     * @param mixed $model
     * @param array $productTypeData 商品類型資料
     * 
     * @return ModelProductType
     */
    public function setModel(mixed $model, array $productTypeData): ModelProductType
    {
        $model->name = $productTypeData['name'];
        $model->status = $productTypeData['status'];

        return $model;
    }
}
