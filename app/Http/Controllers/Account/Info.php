<?php

namespace App\Http\Controllers\Account;

use App\Services\Account\Info as SrcInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * 控制層-帳號資訊
 */
class Info extends Controller
{
    /**
     * 建構子
     * 
     * @param SrcInfo $srcInfo 服務層-帳號資訊
     */
    public function __construct(
        private readonly SrcInfo $srcInfo,
    ) {
    }

    /**
     * 取得帳號資料
     * 
     * @return JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        // 取得帳號ID
        $accountAuth = context()->get('accountAuth');
        $accountId = $accountAuth['accountId'];

        // 取得帳號資料
        $result = $this->srcInfo->getProfile($accountId);

        // 回傳結果
        return $this->toolResponse()
            ->setHttpCodeByStatus($result->status)
            ->setMessage($result->message)
            ->bulkAddData($result->data)
            ->build();
    }
}