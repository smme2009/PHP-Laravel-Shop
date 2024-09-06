<?php

namespace App\Http\Service\Frontend\Cart;

use App\Http\Service\Service;

use App\Http\Repository\Frontend\Cart\Cart as RepoCart;

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
     * @return array
     */
    public function getCartProductList()
    {
        $list = $this->repoCart->getCartProductList();

        $photoIdList = [];
        foreach ($list as $cart) {
            $photoIdList[] = $cart->product->photo_fid;
        }

        $fileInfoList = $this->toolFile()->getFileInfoList($photoIdList);

        $cartProductList = [];
        foreach ($list as $cart) {
            $cartProductList[] = [
                'cartId' => $cart->cart_id,
                'quantity' => $cart->quantity,
                'productId' => $cart->product->product_id,
                'productName' => $cart->product->name,
                'productPhotoUrl' => $fileInfoList[$cart->product->photo_fid]['url'],
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
     * @return \App\Tool\Validation\Result
     */
    public function validateData(array $cartProductList)
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

        $result = $this->toolValidation()->validateData($data, $rule);

        return $result;
    }

    /**
     * 新增購物車商品
     * 
     * @param array $cartProductList
     * 
     * @return bool 是否新增成功
     */
    public function addCartProduct(array $cartProductList)
    {
        $isAdd = $this->repoCart
            ->editCartProduct($cartProductList);

        return $isAdd;
    }

    /**
     * 刪除購物車商品
     * 
     * @param array $cartIdList 購物車ID列表
     * 
     * @return bool
     */
    public function deleteCartProduct(array $cartIdList)
    {
        $isDelete = $this->repoCart
            ->deleteCartProduct($cartIdList);

        return $isDelete;
    }
}
