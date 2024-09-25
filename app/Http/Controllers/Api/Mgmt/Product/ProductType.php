<?php

namespace App\Http\Controllers\Api\Mgmt\Product;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Mgmt\Product\ProductType as SrcProductType;

/**
 * 商品類型
 */
class ProductType extends Controller
{
    public function __construct(
        private SrcProductType $srcProductType,
    ) {
    }

    /**
     * 取得商品類型列表
     * 
     * @return JsonResponse
     */
    public function getProductTypePage(): JsonResponse
    {
        // 取得搜尋資料
        $searchData = [
            'keyword' => request()->get('keyword'),
        ];

        $productTypePage = $this->srcProductType
            ->getProductTypePage($searchData);

        $response = $this->toolResponseJson()
            ->setMessage('成功取得商品類型分頁資料')
            ->setData([
                'productTypePage' => $productTypePage,
            ])
            ->get();

        return $response;
    }

    /**
     * 取得商品類型
     * 
     * @param int $productTypeId
     * 
     * @return JsonResponse
     */
    public function getProductType(int $productTypeId): JsonResponse
    {
        $productType = $this->srcProductType
            ->getProductType($productTypeId);

        if ($productType === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(404)
                ->setMessage('取得商品類型資料失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setMessage('成功取得商品類型資料')
            ->setData([
                'productType' => $productType,
            ])
            ->get();

        return $response;
    }

    /**
     * 新增商品類型
     * 
     * @return JsonResponse
     */
    public function addProductType(): JsonResponse
    {
        $productTypeData = $this->setProductTypeData();

        $result = $this->srcProductType
            ->validateData($productTypeData);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setData([
                    'errorList' => $result->error
                ])
                ->get();

            return $response;
        }

        $productTypeId = $this->srcProductType
            ->addProductType($productTypeData);

        if ($productTypeId === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('新增商品類型失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功新增商品類型')
            ->setData([
                'productTypeId' => $productTypeId,
            ])
            ->get();

        return $response;
    }

    /**
     * 編輯商品類型
     * 
     * @param int $productTypeId 商品類型ID
     * 
     * @return JsonResponse
     */
    public function editProductType(int $productTypeId): JsonResponse
    {
        $productTypeData = $this->setProductTypeData();

        $result = $this->srcProductType
            ->validateData($productTypeData, $productTypeId);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setData(['errorList' => $result->error])
                ->get();

            return $response;
        }

        $isEdit = $this->srcProductType
            ->editProductType($productTypeId, $productTypeData);

        if ($isEdit === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('編輯商品類型失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功編輯商品類型')
            ->get();

        return $response;
    }

    /**
     * 刪除商品類型
     * 
     * @param int $productTypeId 商品類型ID
     * 
     * @return JsonResponse
     */
    public function deleteProductType(int $productTypeId): JsonResponse
    {
        $isDelete = $this->srcProductType
            ->deletePeoductType($productTypeId);

        if ($isDelete === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('刪除商品類型失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功刪除商品類型')
            ->get();

        return $response;
    }

    /**
     * 編輯商品類型狀態
     * 
     * @param int $productTypeId 商品類型ID
     * 
     * @return JsonResponse
     */
    public function editProductTypeStatus(int $productTypeId): JsonResponse
    {
        $status = request()->get('status');

        $isEdit = $this->srcProductType
            ->editProductTypeStatus($productTypeId, $status);

        if ($isEdit === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('編輯商品類型狀態失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功編輯商品類型狀態')
            ->get();

        return $response;
    }
    /**
     * 設定商品類型資料
     * 
     * @return array
     */
    private function setProductTypeData(): array
    {
        $productTypeData = [
            'name' => request()->get('name'),
            'status' => request()->get('status'),
        ];

        return $productTypeData;
    }
}
