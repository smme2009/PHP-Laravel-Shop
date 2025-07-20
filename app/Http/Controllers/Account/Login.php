<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\JsonResponse;
use App\Services\Account\Login as SrcLogin;
use App\Http\Controllers\Controller;

/**
 * 控制層-帳號登入
 */
class Login extends Controller
{
    /**
     * 建構子
     * @param SrcLogin $srcLogin 服務層-帳號登入
     */
    public function __construct(
        private readonly SrcLogin $srcLogin,
    ) {
    }

    /**
     * 登入帳號
     * 
     * @return JsonResponse 回傳結果
     */
    public function login(): JsonResponse
    {
        // 接收請求的資料，為避免錯誤所以皆預設空字串
        $account = request('account', '');
        $password = request('password', '');

        // 登入帳號
        $result = $this->srcLogin->login($account, $password);

        // 回傳結果
        return $this->toolResponse()
            ->setHttpCodeByStatus($result->status)
            ->setMessage($result->message)
            ->bulkAddData($result->data)
            ->build();
    }
}