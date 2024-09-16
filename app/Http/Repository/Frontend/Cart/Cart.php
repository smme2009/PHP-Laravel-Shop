<?php

namespace App\Http\Repository\Frontend\Cart;

use App\Models\Cart as ModelCart;

/**
 * 購物車
 */
class Cart
{
    public function __construct(
        public ModelCart $cart,
    ) {

    }

    /**
     * 取得購物車商品列表
     * 
     * @param null|array $cartId 購物車ID
     * 
     * @return \Illuminate\Database\Eloquent\Collection 購物車商品列表
     */
    public function getCartProductList(null|array $cartId = null)
    {
        $memberId = auth('member')->user()->member_id;

        $cartProductList = $this->cart
            ->where('member_id', $memberId)
            ->when(!is_null($cartId), function ($query) use ($cartId) {
                $query->whereIn('cart_id', $cartId);
            })
            ->orderByDesc('created_at')
            ->get();

        return $cartProductList;
    }

    /**
     * 編輯購物車商品
     * 
     * @param array $productList 商品列表
     *
     * @return bool 是否編輯成功
     */
    public function editCartProduct(array $productList)
    {
        $memberId = auth('member')->user()->member_id;

        $editData = [];
        foreach ($productList as $product) {
            $editData[] = [
                'member_id' => $memberId,
                'product_id' => $product['productId'],
                'quantity' => $product['quantity'],
            ];
        }

        $unique = ['member_id', 'product_id'];
        $result = $this->cart->upsert($editData, $unique);
        $isEdit = ($result === 0) ? false : true;

        return $isEdit;
    }

    /**
     * 刪除購物車商品
     * 
     * @param array $cartIdList 購物車ID列表
     * 
     * @return bool 是否刪除成功
     */
    public function deleteCartProduct(array $cartIdList)
    {
        $memberId = auth('member')->user()->member_id;

        $isDelete = $this->cart
            ->where('member_id', $memberId)
            ->whereIn('cart_id', $cartIdList)
            ->delete();

        return $isDelete;
    }
}
