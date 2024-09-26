<?php

namespace App\Http\Service\Shop\Cart;

use App\Http\Service\Service;
use App\Http\Repository\Shop\Cart\Cart as RepoCart;
use App\Tool\Validation\Result;

/**
 * 購物車
 */
class Cart extends Service
{
    public function __construct(
        private RepoCart $repoCart,
    ) {
    }

    /**  
     * 取得購物車商品列表
     * 
     * @return array 購物車商品列表
     */
    public function getCartProductList(): array
    {
        $list = $this->repoCart
            ->getCartProductList();

        $cartProductList = [];
        foreach ($list as $cart) {
            $productPhotoUrl = $this->toolFile()
                ->getFileUrl($cart->product->productPhoto->path);

            $cartProductList[] = [
                'cartId' => $cart->cart_id,
                'quantity' => $cart->quantity,
                'productId' => $cart->product->product_id,
                'productName' => $cart->product->name,
                'productPhotoUrl' => $productPhotoUrl,
                'productPrice' => $cart->product->price,
                'productQuantity' => $cart->product->quantity,
                'productStatus' => $cart->product->status,
            ];
        }

        return $cartProductList;
    }

    /**
     * 驗證資料
     * 
     * @param array $cartProductList 購物車商品列表
     * 
     * @return Result
     */
    public function validateData(array $cartProductList): Result
    {
        // 驗證資料
        $data = [
            'cartProductList' => $cartProductList,
        ];

        // 驗證規則
        $rule = [
            'cartProductList' => ['required', 'array'],
            'cartProductList.*.productId' => ['required', 'integer'],
            'cartProductList.*.quantity' => ['required', 'integer'],
        ];

        $result = $this->toolValidation()
            ->validateData($data, $rule);

        return $result;
    }

    /**
     * 編輯購物車商品
     * 
     * @param array $cartProductList 購屋車商品列表
     * 
     * @return bool 是否編輯成功
     */
    public function editCartProduct(array $cartProductList): bool
    {
        $isEdit = $this->repoCart
            ->editCartProduct($cartProductList);

        return $isEdit;
    }

    /**
     * 刪除購物車商品
     * 
     * @param array $cartIdList 購物車ID列表
     * 
     * @return bool 是否刪除成功
     */
    public function deleteCartProduct(array $cartIdList): bool
    {
        $isDelete = $this->repoCart
            ->deleteCartProduct($cartIdList);

        return $isDelete;
    }
}
