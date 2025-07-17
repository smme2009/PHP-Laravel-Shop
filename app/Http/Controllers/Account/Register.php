<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\JsonResponse;
use App\Services\Account\Register as SrcRegister;
use App\Http\Controllers\Controller;

/**
 * 控制層-帳號註冊
 */
class Register extends Controller
{
    /**
     * 建構子
     * 
     * @param SrcRegister $srcRegister 服務層-帳號註冊
     */
    public function __construct(
        private readonly SrcRegister $srcRegister,
    ) {
    }

    /**
     * 註冊帳號
     * 
     * @return JsonResponse
     */
    public function register(): JsonResponse
    {
        // 接收請求的資料
        $request = [
            'account' => request('account'),
            'password' => request('password'),
            'name' => request('name'),
        ];

        // 註冊帳號
        $result = $this->srcRegister->register($request);

        // 回傳結果
        return $this->toolResponse()
            ->setHttpCodeByStatus($result->status)
            ->setMessage($result->message)
            ->bulkAddData($result->data)
            ->build();
    }
}