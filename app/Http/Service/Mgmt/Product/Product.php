<?php

namespace App\Http\Service\Mgmt\Product;

use App\Http\Service\Service;
use App\Http\Repository\Mgmt\Product\Product as RepoProduct;
use App\Models\Product as ModelProduct;
use App\Tool\Validation\Result;

/**
 * 商品
 */
class Product extends Service
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
     * @return array 商品分頁
     */
    public function getProductPage(array $searchData): array
    {
        $page = $this->repoProduct
            ->getProductPage($searchData);

        $data = [];
        foreach ($page as $product) {
            $data[] = $this->setProduct($product);
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
     * @return bool|array 商品
     */
    public function getProduct(int $productId): bool|array
    {
        $isSet = $this->repoProduct
            ->setProduct($productId);

        if ($isSet === false) {
            return false;
        }

        $productModel = $this->repoProduct->product;
        $product = $this->setProduct($productModel);

        return $product;
    }

    /**  
     * 驗證資料
     * 
     * @param array $productData 商品資料
     * 
     * @return Result 驗證結果
     */
    public function validateData(array $productData): Result
    {
        // 驗證規則
        $rule = [
            'name' => ['required', 'string'],
            'photoFileId' => ['required', 'integer', 'exists:file,file_id'],
            'price' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
            'description' => ['required', 'string'],
            'pageHtml' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
            'startTime' => ['nullable', 'date', 'date_format:Y-m-d H:i:s'],
            'endTime' => ['nullable', 'date', 'date_format:Y-m-d H:i:s', 'after:startTime'],
            'productTypeId' => ['nullable', 'integer', 'exists:App\Models\ProductType,product_type_id'],
        ];

        $result = $this->toolValidation()
            ->validateData($productData, $rule);

        return $result;
    }

    /**  
     * 驗證照片
     * 
     * @param mixed $photo 照片
     * 
     * @return Result 驗證結果
     */
    public function validatePhoto(mixed $photo): Result
    {
        // 驗證資料
        $data = [
            'photo' => $photo,
        ];

        // 驗證規則
        $rule = [
            'photo' => ['required', 'image', 'max:10240'],
        ];

        $result = $this->toolValidation()
            ->validateData($data, $rule);

        return $result;
    }

    /**
     * 上傳商品圖片
     * 
     * @param mixed $photo 照片
     * 
     * @return bool|array 圖片資訊
     */
    public function uploadProductPhoto(mixed $photo): bool|array
    {
        $fileInfo = $this->toolFile()
            ->uploadFile($photo, 'product');

        return $fileInfo;
    }

    /**
     * 新增商品
     * 
     * @param array $productData 商品資料
     * 
     * @return bool|int 商品ID
     */
    public function addProduct(array $productData): bool|int
    {
        // 新增商品
        $product_id = $this->repoProduct
            ->addProduct($productData);

        return $product_id;
    }

    /**
     * 編輯商品
     * 
     * @param int $productId 商品ID
     * @param array $productData 商品資料
     * 
     * @return bool 是否編輯成功
     */
    public function editProduct(int $productId, array $productData): bool
    {
        $isSet = $this->repoProduct
            ->setProduct($productId, true);

        if (!$isSet) {
            return false;
        }

        // 編輯商品
        $isEdit = $this->repoProduct->editProduct($productData);

        return $isEdit;
    }

    /**
     * 刪除商品
     * 
     * @param int $productId 商品ID
     * 
     * @return bool
     */
    public function deleteProduct(int $productId)
    {
        $isSet = $this->repoProduct->setProduct($productId, true);

        if ($isSet === false) {
            return false;
        }

        // 刪除商品
        $isDelete = $this->repoProduct
            ->deleteProduct();

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
        $isSet = $this->repoProduct
            ->setProduct($productId, true);

        if ($isSet === false) {
            return false;
        }

        $isEdit = $this->repoProduct
            ->editProductStatus($status);

        return $isEdit;
    }

    /**
     * 設定商品資料結構
     * 
     * @param ModelProduct $product 商品資料
     * 
     * @return array 商品資料結構
     */
    private function setProduct(ModelProduct $product): array
    {
        // 上架時間
        $startTime = $product->start_at;
        $startTime = is_null($startTime)
            ? null
            : strtotime($startTime);

        // 下架時間
        $endTime = $product->end_at;
        $endTime = is_null($endTime)
            ? null
            : strtotime($endTime);


        // 圖片網址
        $photoUrl = $this->toolFile()
            ->getFileUrl($product->productPhoto->path);

        $product = [
            'productId' => $product->product_id,
            'name' => $product->name,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'description' => $product->description,
            'pageHtml' => $product->page_html,
            'photoUrl' => $photoUrl,
            'status' => (bool) $product->status,
            'photoFileId' => $product->photo_fid,
            'productTypeId' => $product->product_type_id,
            'productTypeName' => $product->productType->name ?? '',
        ];

        return $product;
    }
}
