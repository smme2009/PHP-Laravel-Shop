<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use App\Services\Admin\Logout as SrcLogout;
use App\Http\Controllers\Controller;

/**
 * 控制層-管理員登出
 */
class Logout extends Controller
{
    /**
     * 建構子
     *
     * @param SrcLogout $srcLogout 服務層-管理員登出
     */
    public function __construct(
        private readonly SrcLogout $srcLogout,
    ) {}

    /**
     * 登出管理員
     *
     * @return JsonResponse 回傳結果
     */
    public function logout(): JsonResponse
    {
        // 登出管理員
        $result = $this->srcLogout->logout();

        // 回傳結果
        return $this->getJsonResponse($result);
    }
}
