<?php

namespace App\Http\Service\Product;

use App\Http\Repository\Product\Product as RepoProduct;

use App\Tool\ValidateData as ToolValidateData;
use App\Tool\File as ToolFile;

/**
 * 商品
 */
class Product
{
    public function __construct(
        private RepoProduct $repoProduct,
    ) {
    }

    /**  
     * 取得商品分頁
     * 
     * @param array $searchData 搜尋資料
     * 
     * @return array
     */
    public function getProductPage(array $searchData): array
    {
        $page = $this->repoProduct->getProductPage($searchData);

        $photoFidList = $page->pluck('photo_fid')->all();
        $fileInfoList = ToolFile::getFileInfoList($photoFidList);

        $data = [];
        foreach ($page as $product) {
            $fileInfo = $fileInfoList[$product->photo_fid];

            $data[] = $this->setProduct($product, $fileInfo);
        }

        $productPage = [
            'data' => $data,
            'total' => $page->total(),
        ];

        return $productPage;
    }

    /**
     * 取得商品
     * 
     * @param int $productId 商品ID
     * 
     * @return false|array
     */
    public function getProduct(int $productId): false|array
    {
        $product = $this->repoProduct->getProduct($productId);

        if (!$product) {
            return false;
        }

        $fileInfo = ToolFile::getFileInfo($product->photo_fid);

        $product = $this->setProduct($product, $fileInfo);

        return $product;
    }

    /**  
     * 驗證資料
     * 
     * @param array $productData 商品資料
     * 
     * @return array
     */
    public function validateData(array $productData): array
    {
        // 驗證規則
        $rule = [
            'name' => ['required', 'string'],
            'photoFileId' => ['required', 'integer', 'exists:file,file_id'],
            'price' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
            'description' => ['required', 'string'],
            'status' => ['required', 'boolean'],
            'startTime' => ['nullable', 'date', 'date_format:Y-m-d H:i:s'],
            'endTime' => ['nullable', 'date', 'date_format:Y-m-d H:i:s', 'after:startTime'],
        ];

        $result = ToolValidateData::validateData($productData, $rule);

        return $result;
    }

    /**  
     * 驗證照片
     * 
     * @param mixed $photo 照片
     * 
     * @return array|false
     */
    public function validatePhoto(mixed $photo): array|false
    {
        // 驗證資料
        $data = [
            'photo' => $photo,
        ];

        // 驗證規則
        $rule = [
            'photo' => ['required', 'image', 'max:10240'],
        ];

        $result = ToolValidateData::validateData($data, $rule);

        return $result;
    }

    /**
     * 上傳商品圖片
     * 
     * @param mixed $photo 照片
     * 
     * @return array
     */
    public function uploadProductPhoto(mixed $photo): array
    {
        $fileInfo = ToolFile::uploadFile($photo, 'product');

        return $fileInfo;
    }

    /**
     * 新增商品
     * 
     * @param array $productData 商品資料
     * 
     * @return int|false 商品ID
     */
    public function addProduct(array $productData): int|false
    {
        // 新增商品
        $product_id = $this->repoProduct->addProduct($productData);

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
        // 編輯商品
        $isEdit = $this->repoProduct->editProduct($productId, $productData);

        return $isEdit;
    }

    /**
     * 刪除商品
     * 
     * @param int $productId 商品ID
     * 
     * @return bool
     */
    public function deletePeoduct(int $productId): bool
    {
        // 刪除商品
        $isDelete = $this->repoProduct->deleteProduct($productId);

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
    public function editProductStatus(int $productId, bool $status): bool
    {
        $isEdit = $this->repoProduct->editProductStatus($productId, $status);

        return $isEdit;
    }

    /**
     * 設定商品資料結構
     * 
     * @param mixed $product 商品資料
     * @param array $fileInfo 商品照片檔案資訊
     * 
     * @return array 商品資料結構
     */
    private function setProduct(mixed $product, array $fileInfo)
    {
        $startTime = $product->start_at;
        $startTime = is_null($startTime) ? null : strtotime($startTime);
        $endTime = $product->end_at;
        $endTime = is_null($endTime) ? null : strtotime($endTime);

        $product = [
            'productId' => $product->product_id,
            'name' => $product->name,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'description' => $product->description,
            'photoUrl' => $fileInfo['url'],
            'status' => (bool) $product->status,
        ];

        return $product;
    }
}
