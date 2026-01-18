<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\JsonResponse;
use App\Services\Account\Address as SrcAddress;
use App\Http\Controllers\Controller;

/**
 * 控制層-帳號地址
 */
class Address extends Controller
{
    /**
     * 建構子
     * 
     * @param SrcAddress $srcAddress 服務層-帳號地址
     */
    public function __construct(
        private readonly SrcAddress $srcAddress
    ) {
    }

    /**
     * 新增帳號地址
     * 
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        // 取得帳號資料
        $accountAuth = context()->get('accountAuth');

        // 接收請求資料
        $data = [
            'address' => request('address'),
            'account_id' => $accountAuth['accountId']
        ];

        // 新增帳號地址
        $result = $this->srcAddress->create($data);

        // 回傳結果
        return $this->getJsonResponse($result);
    }

    /**
     * 取得帳號地址列表
     * 
     * @return JsonResponse
     */
    public function getList(): JsonResponse
    {
        // 取得帳號資料
        $accountAuth = context()->get('accountAuth');

        // 取得帳號地址列表
        $result = $this->srcAddress->getList($accountAuth['accountId']);

        // 回傳結果
        return $this->getJsonResponse($result);
    }

    /**
     * 刪除帳號地址
     * 
     * @param int $accountAddressId 帳號地址ID
     * 
     * @return JsonResponse
     */
    public function delete(int $accountAddressId): JsonResponse
    {
        // 取得帳號資料
        $accountAuth = context()->get('accountAuth');

        // 刪除帳號地址
        $result = $this->srcAddress->delete($accountAuth['accountId'], $accountAddressId);

        // 回傳結果
        return $this->getJsonResponse($result);
    }
}