<?php

namespace App\Http\Repository\Product;

use App\Models\Product as ModelProduct;

/**
 * 商品
 */
class Product
{
    /**
     * 取得商品分頁
     * 
     * @param array $searchData 搜尋資料
     * 
     * @return mixed
     */
    public function getProductPage(array $searchData): mixed
    {
        $productPage = ModelProduct::when(
            $searchData['keyword'],
            function ($query) use ($searchData) {
                $query->where('name', 'like', '%' . $searchData['keyword'] . '%');
            }
        )
            ->orderByDesc('product_id')
            ->paginate();

        return $productPage;
    }

    /**
     * 取得商品
     * 
     * @param int $productId 商品ID
     * 
     * @return mixed
     */
    public function getProduct(int $productId): mixed
    {
        $product = ModelProduct::find($productId);

        return $product;
    }

    /**
     * 新增商品
     * 
     * @param array $productData 商品資料
     * 
     * @return int|false
     */
    public function addProduct(array $productData): int|false
    {
        $model = new ModelProduct();

        $model = $this->setModel($model, $productData);

        $isSave = $model->save();

        if (!$isSave) {
            return false;
        }

        $product_id = $model->product_id;

        return $product_id;
    }

    /**
     * 編輯商品
     * 
     * @param int $productId 商品ID
     * @param array $productData 商品資料
     * 
     * @return bool
     */
    public function editProduct(int $productId, array $productData): bool
    {
        $model = ModelProduct::lockForUpdate()
            ->find($productId);

        if (!$model) {
            return false;
        }

        $model = $this->setModel($model, $productData);

        $isSave = $model->save();

        return $isSave;
    }

    /**
     * 刪除商品
     * 
     * @param int $productId 商品ID
     * 
     * @return bool
     */
    public function deleteProduct(int $productId): bool
    {
        $model = ModelProduct::lockForUpdate()
            ->find($productId);

        if (!$model) {
            return false;
        }

        $isDelete = $model->delete();

        return $isDelete;
    }

    /**
     * 編輯商品狀態
     * 
     * @param int $productId 商品ID
     * @param bool $status 狀態
     * 
     * @return bool 是否編輯成功
     */
    public function editProductStatus(int $productId, bool $status)
    {
        $model = ModelProduct::lockForUpdate()
            ->find($productId);

        if (!$model) {
            return false;
        }

        $model->status = $status;

        $isEdit = $model->save();

        return $isEdit;
    }

    /**
     * 設定商品Model
     * 
     * @param mixed $model
     * @param array $productData 商品資料
     * 
     * @return mixed
     */
    public function setModel(mixed $model, array $productData): mixed
    {
        $model->name = $productData['name'];
        $model->photo_fid = $productData['photoFileId'];
        $model->price = $productData['price'];
        $model->quantity = $productData['quantity'];
        $model->description = $productData['description'];
        $model->status = $productData['status'];
        $model->start_at = $productData['startTime'];
        $model->end_at = $productData['endTime'];
        $model->product_type_id = $productData['productTypeId'];

        return $model;
    }
}
