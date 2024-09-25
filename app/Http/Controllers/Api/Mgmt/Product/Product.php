<?php

namespace App\Http\Controllers\Api\Mgmt\Product;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Mgmt\Product\Product as SrcProduct;

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
     * @return JsonResponse
     */
    public function getProductPage(): JsonResponse
    {
        // 取得搜尋資料
        $searchData = [
            'keyword' => request()->get('keyword'),
        ];

        $productPage = $this->srcProduct
            ->getProductPage($searchData);

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
     * @param int $productId 商品ID
     * 
     * @return JsonResponse
     */
    public function getProduct(int $productId): JsonResponse
    {
        $product = $this->srcProduct
            ->getProduct($productId);

        if ($product === false) {
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
     * @return JsonResponse
     */
    public function uploadProductPhoto(): JsonResponse
    {
        $photo = request()->file('photo');

        $result = $this->srcProduct
            ->validatePhoto($photo);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result->message)
                ->get();

            return $response;
        }

        $fileInfo = $this->srcProduct
            ->uploadProductPhoto($photo);

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
     * @return JsonResponse
     */
    public function addProduct(): JsonResponse
    {
        $productData = $this->setProductData();

        $result = $this->srcProduct
            ->validateData($productData);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setData(['errorList' => $result->error])
                ->get();

            return $response;
        }

        $productId = $this->srcProduct
            ->addProduct($productData);

        if ($productId === false) {
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
     * @return JsonResponse
     */
    public function editProduct(int $productId): JsonResponse
    {
        $productData = $this->setProductData();

        $result = $this->srcProduct
            ->validateData($productData);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setData(['errorList' => $result->error])
                ->get();

            return $response;
        }

        DB::beginTransaction();

        $isEdit = $this->srcProduct
            ->editProduct($productId, $productData);

        if (!$isEdit) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('編輯商品失敗')
                ->get();

            return $response;
        }

        DB::commit();

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
     * @return JsonResponse
     */
    public function deleteProduct(int $productId): JsonResponse
    {
        DB::beginTransaction();

        $isDelete = $this->srcProduct
            ->deleteProduct($productId);

        if (!$isDelete) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('刪除商品失敗')
                ->get();

            return $response;
        }

        DB::commit();

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
     * @return JsonResponse
     */
    public function editProductStatus(int $productId): JsonResponse
    {
        $status = request()->get('status');

        DB::beginTransaction();

        $isEdit = $this->srcProduct
            ->editProductStatus($productId, $status);

        if (!$isEdit) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('編輯商品狀態失敗')
                ->get();

            return $response;
        }

        DB::commit();

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
    private function setProductData(): array
    {
        $productData = [
            'name' => request()->get('name'),
            'photoFileId' => request()->get('photoFileId'),
            'price' => request()->get('price'),
            'quantity' => request()->get('quantity'),
            'description' => request()->get('description'),
            'pageHtml' => request()->get('pageHtml'),
            'status' => request()->get('status'),
            'startTime' => request()->get('startTime'),
            'endTime' => request()->get('endTime'),
            'productTypeId' => request()->get('productTypeId'),
        ];

        return $productData;
    }
}
