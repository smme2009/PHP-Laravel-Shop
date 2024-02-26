<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Tool\Jwt as ToolJwt;
use App\Tool\Response\Json as ToolResponseJson;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 驗證Jwt Token
        $jwtToken = $this->getJwtToken();

        $data = ToolJwt::decode($jwtToken);

        if (!$data) {
            $response = ToolResponseJson::init()
                ->setHttpCode(400)
                ->setMessage('Token驗證失敗')
                ->get();

            return $response;
        }

        // 登入帳號
        $isLogin = auth()->loginUsingId($data['userId']);

        if (!$isLogin) {
            $response = ToolResponseJson::init()
                ->setHttpCode(400)
                ->setMessage('登入失敗')
                ->get();

            return $response;
        }

        // 驗證帳號狀態
        $user = auth()->user();

        if (!$user->status) {
            $response = ToolResponseJson::init()
                ->setHttpCode(400)
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
    private function getJwtToken(): string
    {
        $authorization = request()->header('Authorization');

        $match = [];
        preg_match('/^Bearer (.+)$/', $authorization, $match);
        $jwtToken = $match[1] ?? '';

        return $jwtToken;
    }
}
