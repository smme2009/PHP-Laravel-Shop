<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\JsonResponse;
use App\Services\Account\Logout as SrcLogout;
use App\Http\Controllers\Controller;

/**
 * 控制層-帳號登出
 */
class Logout extends Controller
{
    /**
     * 建構子
     * 
     * @param SrcLogout $srcLogout 服務層-帳號登出
     */
    public function __construct(
        private readonly SrcLogout $srcLogout,
    ) {
    }

    /**
     * 登出帳號
     * 
     * @return JsonResponse 回傳結果
     */
    public function logout(): JsonResponse
    {
        // 登出帳號
        $result = $this->srcLogout->logout();

        // 回傳結果
        return $this->getJsonResponse($result);
    }
}
