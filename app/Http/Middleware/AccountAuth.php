<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param string $role 角色
     * 
     * @return Response 框架的Response物件
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 取得JWT Token
        $jwtToken = $this->getJwtToken();

        // 驗證Jwt Token
        $ucfRole = ucfirst($role);
        $class = 'App\Services\\' . $ucfRole . '\\Auth';
        $srcAuth = app()->make($class);
        $result = $srcAuth->checkByJwtToken($jwtToken);

        // 驗證失敗，回傳錯誤資訊
        if ($result->httpCode === 401) {
            $responseData = [
                'message' => $result->message,
                'data' => $result->data,
            ];

            return response()->json($responseData, 401);
        }

        // 帳號資訊
        $authData = [
            'jwtToken' => $jwtToken,
            'role' => $result->data['role'],
            'id' => $result->data['id'],
        ];

        // 將帳號資訊寫入上下文
        context()->add('authData', $authData);

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
