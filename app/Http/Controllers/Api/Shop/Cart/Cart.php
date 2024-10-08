<?php

namespace App\Http\Controllers\Api\Shop\Cart;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Shop\Cart\Cart as SrcCart;

/**
 * 購物車
 */
class Cart extends Controller
{
    public function __construct(
        private SrcCart $srcCart,
    ) {
    }

    /**
     * 取得購物車商品列表
     * 
     * @return JsonResponse
     */
    public function getCartProductList(): JsonResponse
    {
        $cartProductList = $this->srcCart
            ->getCartProductList();

        $response = $this->toolResponseJson()
            ->setMessage('成功取得購物車商品列表')
            ->setData([
                'cartProductList' => $cartProductList,
            ])
            ->get();

        return $response;
    }

    /**
     * 編輯購物車商品
     * 
     * @return JsonResponse
     */
    public function editCartProduct(): JsonResponse
    {
        $cartProductList = request()->get('cartProductList');

        $result = $this->srcCart
            ->validateData($cartProductList);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage($result->message)
                ->get();

            return $response;
        }

        $isEdit = $this->srcCart
            ->editCartProduct($cartProductList);

        if ($isEdit === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('編輯購物車商品失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功編輯購物車商品')
            ->get();

        return $response;
    }

    /**
     * 刪除購物車商品
     * 
     * @return JsonResponse
     */
    public function deleteCartProduct(): JsonResponse
    {
        $cartIdList = request()->get('cartIdList');

        $isDelete = $this->srcCart
            ->deleteCartProduct($cartIdList);

        if ($isDelete === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('刪除購物車商品失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('成功刪除商品商品')
            ->get();

        return $response;
    }
}
