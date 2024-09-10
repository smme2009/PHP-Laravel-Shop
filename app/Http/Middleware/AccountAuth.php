<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Tool\Jwt\Jwt as ToolJwt;
use App\Tool\Response\Json as ToolResponseJson;

class AccountAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $guard): Response
    {
        // Response Json 工具
        $toolResponseJson = new ToolResponseJson();

        // Jwt 工具
        $toolJwt = new ToolJwt();

        // 驗證Jwt Token
        $jwtToken = $this->getJwtToken();
        $data = $toolJwt->decode($jwtToken);

        if (!$data) {
            $response = $toolResponseJson
                ->setHttpCode(401)
                ->setMessage('Token驗證失敗')
                ->get();

            return $response;
        }

        // 登入帳號
        $isLogin = auth($guard)->loginUsingId($data['accountId']);

        if (!$isLogin) {
            $response = $toolResponseJson
                ->setHttpCode(401)
                ->setMessage('登入失敗')
                ->get();

            return $response;
        }

        // 驗證帳號狀態
        $user = auth($guard)->user();

        if (!$user->status) {
            $response = $toolResponseJson
                ->setHttpCode(401)
                ->setMessage('帳號已被關閉')
                ->get();

            return $response;
        }

        $response = $next($request);

        return $response;
    }

    /**
     * 取得JwtToken
     * 
     * @return string
     */
    private function getJwtToken()
    {
        $authorization = request()->header('Authorization');

        $match = [];
        preg_match('/^Bearer (.+)$/', $authorization, $match);
        $jwtToken = $match[1] ?? '';

        return $jwtToken;
    }
}
