<?php

namespace App\Http\Controllers\Api\Backend\Product;

use App\Http\Controllers\Controller;

use App\Http\Service\Product\Product as SrcProduct;

use App\Tool\Response\Json as ToolResponseJson;

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

        $response = ToolResponseJson::init()
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

        $response = ToolResponseJson::init()
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
            $response = ToolResponseJson::init()
                ->setHttpCode(400)
                ->setMessage($result['errorMessage'])
                ->get();

            return $response;
        }

        $fileInfo = $this->srcProduct->uploadProductPhoto($photo);

        if (!$fileInfo) {
            $response = ToolResponseJson::init()
                ->setHttpCode(400)
                ->setMessage('上傳商品圖片失敗')
                ->get();

            return $response;
        }

        $response = ToolResponseJson::init()
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
        $productData = [
            'name' => request()->get('name'),
            'photo_file' => request()->file('photo_file'),
            'price' => request()->get('price'),
            'quantity' => request()->get('quantity'),
            'description' => request()->get('description'),
            'status' => request()->get('status'),
        ];

        $result = $this->srcProduct->validateData($productData);

        if (!$result['status']) {
            $response = ToolResponseJson::init()
                ->setHttpCode(400)
                ->setMessage($result['errorMessage'])
                ->get();

            return $response;
        }

        $productId = $this->srcProduct->addProduct($productData);

        if (!$productId) {
            $response = ToolResponseJson::init()
                ->setHttpCode(400)
                ->setMessage('新增商品失敗')
                ->get();

            return $response;
        }

        $response = ToolResponseJson::init()
            ->setHttpCode(200)
            ->setMessage('成功新增商品')
            ->setData([
                'productId' => $productId,
            ])
            ->get();

        return $response;
    }
}
