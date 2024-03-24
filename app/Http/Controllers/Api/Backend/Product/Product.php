<?php

namespace App\Http\Controllers\Api\Backend\Product;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Http\Service\Product\Product as SrcProduct;

/**
 * 商品
 */
class Product extends Controller
{
    public function __construct(
        private SrcProduct $srcProduct,
    ) {
    }

    /**
     * 取得商品列表
     * 
     * @return mixed
     */
    public function getProductPage(): mixed
    {
        $productPage = $this->srcProduct->getProductPage();

        $response = $this->toolResponseJson()
            ->setMessage('成功取得商品分頁資料')
            ->setData([
                'productPage' => $productPage,
            ])
            ->get();

        return $response;
    }

    /**
     * 取得商品
     * 
     * @param int $productId
     * 
     * @return mixed
     */
    public function getProduct(int $productId): mixed
    {
        $product = $this->srcProduct->getProduct($productId);

        $response = $this->toolResponseJson()
            ->setMessage('成功取得商品資料')
            ->setData([
                'product' => $product,
            ])
            ->get();

        return $response;
    }

    /**
     * 上傳商品圖片
     * 
     * @return mixed
     */
    public function uploadProductPhoto(): mixed
    {
        $photo = request()->file('photo');

        $result = $this->srcProduct->validatePhoto($photo);

        if (!$result['status']) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result['errorMessage'])
                ->get();

            return $response;
        }

        $fileInfo = $this->srcProduct->uploadProductPhoto($photo);

        if (!$fileInfo) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('上傳商品圖片失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功上傳商品圖片')
            ->setData([
                'fileInfo' => $fileInfo,
            ])
            ->get();

        return $response;
    }

    /**
     * 新增商品
     * 
     * @return mixed
     */
    public function addProduct(): mixed
    {
        $productData = $this->setProductData();

        $result = $this->srcProduct->validateData($productData);

        if (!$result['status']) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result['errorMessage'])
                ->get();

            return $response;
        }

        $productId = $this->srcProduct->addProduct($productData);

        if (!$productId) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('新增商品失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功新增商品')
            ->setData([
                'productId' => $productId,
            ])
            ->get();

        return $response;
    }

    /**
     * 編輯商品
     * 
     * @param int $productId 商品ID
     * 
     * @return mixed
     */
    public function editProduct(int $productId): mixed
    {
        $productData = $this->setProductData();

        $result = $this->srcProduct->validateData($productData);

        if (!$result['status']) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result['errorMessage'])
                ->get();

            return $response;
        }

        DB::beginTransaction();

        $isEdit = $this->srcProduct->editProduct($productId, $productData);

        DB::commit();

        if (!$isEdit) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('編輯商品失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功編輯商品')
            ->get();

        return $response;
    }

    /**
     * 刪除商品
     * 
     * @param int $productId 商品ID
     * 
     * @return mixed
     */
    public function deleteProduct(int $productId)
    {
        DB::beginTransaction();

        $isDelete = $this->srcProduct->deletePeoduct($productId);

        DB::commit();

        if (!$isDelete) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('刪除商品失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功刪除商品')
            ->get();

        return $response;
    }

    /**
     * 設定商品資料
     * 
     * @return array
     */
    private function setProductData(): array
    {
        $productData = [
            'name' => request()->get('name'),
            'photoFileId' => request()->get('photoFileId'),
            'price' => request()->get('price'),
            'quantity' => request()->get('quantity'),
            'description' => request()->get('description'),
            'status' => request()->get('status'),
        ];

        return $productData;
    }
}
