<?php

namespace App\Http\Service\Backend\Product;

use App\Http\Service\Service;
use Illuminate\Validation\Rule;

use App\Http\Repository\Backend\Product\ProductType as RepoProductType;

/**
 * 商品類型
 */
class ProductType extends Service
{
    public function __construct(
        private RepoProductType $repoProductType,
    ) {
    }

    /**  
     * 取得商品類型分頁
     * 
     * @param array $searchData 搜尋資料
     * 
     * @return array 商品類型分頁
     */
    public function getProductTypePage(array $searchData)
    {
        $page = $this->repoProductType
            ->getProductTypePage($searchData);

        $data = [];
        foreach ($page as $productType) {
            $data[] = $this->setProductType($productType);
        }

        $productPage = [
            'data' => $data,
            'total' => $page->total(),
        ];

        return $productPage;
    }

    /**
     * 取得商品類型
     * 
     * @param int $productTypeId 商品類型ID
     * 
     * @return false|array 商品類型
     */
    public function getProductType(int $productTypeId)
    {
        $isSet = $this->repoProductType
            ->setProductType($productTypeId);

        if (!$isSet) {
            return false;
        }

        $productTypeModel = $this->repoProductType->productType;

        $productType = $this->setProductType($productTypeModel);

        return $productType;
    }

    /**  
     * 驗證資料
     * 
     * @param array $productTypeData 商品類型資料
     * @param null|int $productTypeId 商品類型ID
     * 
     * @return \App\Tool\Validation\Result 驗證結果
     */
    public function validateData(array $productTypeData, null|int $productTypeId = null)
    {
        // 驗證規則
        $rule = [
            'name' => ['required', 'string'],
            'status' => ['required', 'boolean'],
        ];

        $ruleNameUnique = Rule::unique('App\Models\ProductType', 'name');

        if ($productTypeId) {
            $ruleNameUnique->ignore($productTypeId, 'product_type_id');
        }

        $rule['name'][] = $ruleNameUnique;

        $result = $this->toolValidation()->validateData($productTypeData, $rule);

        return $result;
    }

    /**
     * 新增商品類型
     * 
     * @param array $productTypeData 商品類型資料
     * 
     * @return false|int 商品類型ID
     */
    public function addProductType(array $productTypeData)
    {
        // 新增商品類型
        $product_id = $this->repoProductType
            ->addProductType($productTypeData);

        return $product_id;
    }

    /**
     * 編輯商品類型
     * 
     * @param int $productTypeId 商品類型ID
     * @param array $productTypeData 商品類型資料
     * 
     * @return bool 是否編輯成功
     */
    public function editProductType(int $productTypeId, array $productTypeData)
    {
        $isSet = $this->repoProductType
            ->setProductType($productTypeId);

        if (!$isSet) {
            return false;
        }

        // 編輯商品類型
        $isEdit = $this->repoProductType
            ->editProductType($productTypeData);

        return $isEdit;
    }

    /**
     * 刪除商品類型
     * 
     * @param int $productTypeId 商品類型ID
     * 
     * @return bool 是否刪除成功
     */
    public function deletePeoductType(int $productTypeId)
    {
        $isSet = $this->repoProductType
            ->setProductType($productTypeId);

        if (!$isSet) {
            return false;
        }

        // 刪除商品類型
        $isDelete = $this->repoProductType->deleteProductType();

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
    public function editProductTypeStatus(int $productTypeId, bool $status)
    {
        $isSet = $this->repoProductType
            ->setProductType($productTypeId);

        if (!$isSet) {
            return false;
        }

        $isEdit = $this->repoProductType
            ->editProductTypeStatus($status);

        return $isEdit;
    }

    /**
     * 設定商品類型資料結構
     * 
     * @param mixed $productType 商品類型資料
     * 
     * @return array 商品類型資料結構
     */
    private function setProductType(mixed $productType)
    {
        $productType = [
            'productTypeId' => $productType->product_type_id,
            'name' => $productType->name,
            'status' => (bool) $productType->status,
        ];

        return $productType;
    }
}
