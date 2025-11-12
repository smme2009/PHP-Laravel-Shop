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
        // 接收請求的資料
        $request = [
            'account' => request('account'), // 帳號
            'password' => request('password'), // 密碼
            'roleId' => request('roleId'), // 角色ID
        ];

        // 登入帳號
        $result = $this->srcLogin->login($request);

        // 回傳結果
        return $this->getJsonResponse($result);
    }
}