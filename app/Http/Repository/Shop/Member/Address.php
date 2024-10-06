<?php

namespace App\Http\Repository\Shop\Member;

use Illuminate\Database\Eloquent\Collection;
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
     * @return Collection 購物車商品列表
     */
    public function getMamberAddressList(): Collection
    {
        $memberId = auth('member')
            ->user()
            ->member_id;

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
     * @return bool|int 會員地址ID
     */
    public function addMemberAddress(string $address): bool|int
    {
        $memberId = auth('member')
            ->user()
            ->member_id;

        $this->memberAddress->member_id = $memberId;
        $this->memberAddress->address = $address;
        $isSave = $this->memberAddress->save();

        if ($isSave === false) {
            return false;
        }

        $memberAddressId = $this->memberAddress->member_address_id;

        return $memberAddressId;
    }
}
