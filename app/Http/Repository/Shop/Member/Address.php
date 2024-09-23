<?php

namespace App\Http\Repository\Shop\Member;

use App\Models\MemberAddress as ModelMemberAddress;

/**
 * 會員地址
 */
class Address
{
    public function __construct(
        public ModelMemberAddress $memberAddress,
    ) {

    }

    /**
     * 取得會員地址列表
     * 
     * @return \Illuminate\Database\Eloquent\Collection 購物車商品列表
     */
    public function getMamberAddressList()
    {
        $memberId = auth('member')->user()->member_id;

        $memberAddressList = $this->memberAddress
            ->where('member_id', $memberId)
            ->orderByDesc('created_at')
            ->get();

        return $memberAddressList;
    }

    /**
     * 新增會員地址
     * 
     * @param string $address 地址
     * 
     * @return int 會員地址ID
     */
    public function addMemberAddress(string $address)
    {
        $memberId = auth('member')->user()->member_id;

        $this->memberAddress->member_id = $memberId;
        $this->memberAddress->address = $address;
        $this->memberAddress->save();

        $memberAddressId = $this->memberAddress->member_address_id;

        return $memberAddressId;
    }
}
