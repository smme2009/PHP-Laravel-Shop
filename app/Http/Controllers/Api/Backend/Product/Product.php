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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductPage()
    {
        // 取得搜尋資料
        $searchData = [
            'keyword' => request()->get('keyword'),
        ];

        $productPage = $this->srcProduct->getProductPage($searchData);

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProduct(int $productId)
    {
        $product = $this->srcProduct->getProduct($productId);

        if (!$product) {
            $response = $this->toolResponseJson()
                ->setHttpCode(404)
                ->setMessage('取得商品資料失敗')
                ->get();

            return $response;
        }

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadProductPhoto()
    {
        $photo = request()->file('photo');

        $result = $this->srcProduct->validatePhoto($photo);

        if (!$result->status) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result->message)
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function addProduct()
    {
        $productData = $this->setProductData();

        $result = $this->srcProduct->validateData($productData);

        if (!$result->status) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result->message)
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProduct(int $productId)
    {
        $productData = $this->setProductData();

        $result = $this->srcProduct->validateData($productData);

        if (!$result->status) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result->message)
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
     * @return \Illuminate\Http\JsonResponse
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
     * 編輯商品狀態
     * 
     * @param int $productId 商品ID
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProductStatus(int $productId)
    {
        $status = request()->get('status');

        DB::beginTransaction();

        $isEdit = $this->srcProduct->editProductStatus($productId, $status);

        DB::commit();

        if (!$isEdit) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('編輯商品狀態失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功編輯商品狀態')
            ->get();

        return $response;
    }
    /**
     * 設定商品資料
     * 
     * @return array 商品資料
     */
    private function setProductData()
    {
        $productData = [
            'name' => request()->get('name'),
            'photoFileId' => request()->get('photoFileId'),
            'price' => request()->get('price'),
            'quantity' => request()->get('quantity'),
            'description' => request()->get('description'),
            'status' => request()->get('status'),
            'startTime' => request()->get('startTime'),
            'endTime' => request()->get('endTime'),
            'productTypeId' => request()->get('productTypeId'),
        ];

        return $productData;
    }
}
