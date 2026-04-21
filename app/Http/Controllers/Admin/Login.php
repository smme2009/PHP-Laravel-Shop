<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use App\Services\Admin\Login as SrcLogin;
use App\Http\Controllers\Controller;

/**
 * 控制層-管理員登入
 */
class Login extends Controller
{
    /**
     * 建構子
     *
     * @param SrcLogin $srcLogin 服務層-管理員登入
     */
    public function __construct(
        private readonly SrcLogin $srcLogin,
    ) {}

    /**
     * 登入
     *
     * @return JsonResponse 回傳結果
     */
    public function login(): JsonResponse
    {
        // 接收請求的資料
        $request = [
            'account' => request('account'), // 帳號
            'password' => request('password'), // 密碼
        ];

        // 登入管理員
        $result = $this->srcLogin->login($request);

        // 回傳結果
        return $this->getJsonResponse($result);
    }
}
