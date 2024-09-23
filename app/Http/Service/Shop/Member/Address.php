<?php

namespace App\Http\Service\Shop\Member;

use App\Http\Service\Service;
use App\Http\Repository\Shop\Member\Address as RepoMemberAddress;

/**
 * 會員地址
 */
class Address extends Service
{
    public function __construct(
        private RepoMemberAddress $repoMemberAddress,
    ) {
    }

    /**  
     * 取得會員地址列表
     * 
     * @return array
     */
    public function getMemberAddressList()
    {
        $list = $this->repoMemberAddress
            ->getMamberAddressList();

        $memberAddressList = [];
        foreach ($list as $memberAddress) {
            $memberAddressList[] = [
                'memberAddressId' => $memberAddress->member_address_id,
                'address' => $memberAddress->address,
            ];
        }

        return $memberAddressList;
    }

    /**
     * 驗證資料
     * 
     * @param null|string $address 地址
     * 
     * @return \App\Tool\Validation\Result
     */
    public function validateData(null|string $address)
    {
        // 驗證資料
        $data = [
            'address' => $address,
        ];

        // 驗證規則
        $rule = [
            'address' => ['required', 'string'],
        ];

        $result = $this->toolValidation()
            ->validateData($data, $rule);

        return $result;
    }

    /**
     * 新增會員地址
     * 
     * @param string $address
     * 
     * @return false|int 是否新增成功
     */
    public function addMemberAddress(string $address)
    {
        $memberAddressId = $this->repoMemberAddress
            ->addMemberAddress($address);

        if (!$memberAddressId) {
            return false;
        }

        return $memberAddressId;
    }
}
