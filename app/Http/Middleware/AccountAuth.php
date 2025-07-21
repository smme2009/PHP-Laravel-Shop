<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Account\Auth as SrcAuth;
use App\Http\Controllers\Tool\Response as CtrlToolResponse;

/**
 * 中介層-帳號驗證
 */
class AccountAuth
{
    /**
     * 處理傳入的Request
     * 
     * @param Request $request 框架的Request物件
     * @param Closure $next 將要被執行的控制層
     * 
     * @return Response 框架的Response物件
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 依賴 
        $srcAuth = app()->make(SrcAuth::class);
        $ctrlToolResponse = app()->make(CtrlToolResponse::class);

        // 驗證Jwt Token
        $result = $srcAuth->checkByJwtToken($this->getJwtToken());

        // 驗證失敗，回傳錯誤資訊
        if ($result->status === false) {
            return $ctrlToolResponse
                ->setHttpCode(401)
                ->setMessage($result->message)
                ->build();
        }

        // 帳號資訊
        $accountAuth = [
            'accountId' => $result->data['accountId'],
            'roleIds' => $result->data['roleIds'],
        ];

        // 將帳號資訊寫入上下文
        context()->add('accountAuth', $accountAuth);

        return $next($request);
    }

    /**
     * 取得JWT Token
     * 
     * @return string JWT Token
     */
    private function getJwtToken(): string
    {
        $auth = request()->header('Authorization');

        $match = [];
        preg_match('/^Bearer (.+)$/', $auth, $match);

        return $match[1] ?? '';
    }
}
