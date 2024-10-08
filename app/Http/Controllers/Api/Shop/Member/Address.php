<?php

namespace App\Http\Controllers\Api\Shop\Member;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Shop\Member\Address as SrcMemberAddress;

/**
 * 會員地址
 */
class Address extends Controller
{
    public function __construct(
        private SrcMemberAddress $srcMemberAddress,
    ) {
    }

    /**
     * 取得會員地址列表
     * 
     * @return JsonResponse
     */
    public function getMemberAddressList(): JsonResponse
    {
        $memberAddressList = $this->srcMemberAddress
            ->getMemberAddressList();

        $response = $this->toolResponseJson()
            ->setMessage('成功取得會員地址列表')
            ->setData([
                'memberAddressList' => $memberAddressList,
            ])
            ->get();

        return $response;
    }

    /**
     * 新增會員地址
     * 
     * @return JsonResponse
     */
    public function addMemberAddress(): JsonResponse
    {
        $address = request()->get('address');

        $result = $this->srcMemberAddress
            ->validateData($address);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setData([
                    'errorList' => $result->error,
                ])
                ->get();

            return $response;
        }

        $memberAddressId = $this->srcMemberAddress
            ->addMemberAddress($address);

        if ($memberAddressId === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('新增會員地址失敗')
                ->get();

            return $response;
        }

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('新增會員地址成功')
            ->setData([
                'memberAddressId' => $memberAddressId,
            ])
            ->get();

        return $response;
    }
}
