<?php

namespace App\Http\Repository\Backend\Product;

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
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 身品分頁資料
     */
    public function getProductPage(array $searchData)
    {
        $productPage = $this->product
            ->when(
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
     * 設定商品資料
     * 
     * @param int $productId 商品ID
     * @param bool $isLock 是否鎖表
     * 
     * @return bool 是否設定成功
     */
    public function setProduct(int $productId, bool $isLock = false)
    {
        if ($isLock) {
            $this->product->lockForUpdate();
        }

        $product = $this->product->find($productId);

        if (!$product) {
            return false;
        }

        $this->product = $product;

        return true;
    }

    /**
     * 新增商品
     * 
     * @param array $productData 商品資料
     * 
     * @return false|int 商品ID
     */
    public function addProduct(array $productData)
    {
        $isSave = $this->saveModel($productData);

        if (!$isSave) {
            return false;
        }

        $productId = $this->product->product_id;

        return $productId;
    }

    /**
     * 編輯商品
     * 
     * @param array $productData 商品資料
     * 
     * @return bool 是否編輯成功
     */
    public function editProduct(array $productData)
    {
        $isSave = $this->saveModel($productData);

        return $isSave;
    }

    /**
     * 刪除商品
     * 
     * @return bool 是否刪除成功
     */
    public function deleteProduct()
    {
        $isDelete = $this->product->delete();

        return $isDelete;
    }

    /**
     * 編輯商品狀態
     * 
     * @param bool $status 狀態
     * 
     * @return bool 是否編輯成功
     */
    public function editProductStatus(bool $status)
    {
        $this->product->status = $status;

        $isEdit = $this->product->save();

        return $isEdit;
    }

    /**
     * 編輯商品數量
     * 
     * @param bool $type 類型(0為減少，1為增加)
     * @param int $quantity 數量
     * 
     * @return bool 是否編輯成功
     */
    public function editProductQuantity(bool $type, int $quantity)
    {
        $isEdit = false;
        if ($type) {
            $isEdit = $this->product->increment('quantity', $quantity);
        } else {
            // 若減少數量小於零，則判定為失敗
            if ($quantity <= $this->product->quantity) {
                $isEdit = $this->product->decrement('quantity', $quantity);
            }
        }

        return $isEdit;
    }

    /**
     * 儲存商品Model
     * 
     * @param array $productData 商品資料
     * 
     * @return bool 是否儲存成功
     */
    private function saveModel(array $productData)
    {
        $this->product->name = $productData['name'];
        $this->product->photo_fid = $productData['photoFileId'];
        $this->product->price = $productData['price'];
        $this->product->quantity = $productData['quantity'];
        $this->product->description = $productData['description'];
        $this->product->status = $productData['status'];
        $this->product->start_at = $productData['startTime'];
        $this->product->end_at = $productData['endTime'];
        $this->product->product_type_id = $productData['productTypeId'];

        $isSave = $this->product->save();

        return $isSave;
    }
}
