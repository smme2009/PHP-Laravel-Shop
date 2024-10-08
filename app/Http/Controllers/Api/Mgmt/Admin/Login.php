<?php

namespace App\Http\Controllers\Api\Mgmt\Admin;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Service\Mgmt\Admin\Login as SrcLogin;

class Login extends Controller
{
    public function __construct(
        private SrcLogin $srcLogin,
    ) {
    }

    /**
     * 登入
     * 
     * @return JsonResponse
     */
    public function login(): JsonResponse
    {
        $requestData = [
            'account' => request()->get('account'),
            'password' => request()->get('password'),
        ];

        // 驗證資料
        $result = $this->srcLogin
            ->validateData($requestData);

        if ($result->status === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setData([
                    'errorList' => $result->error,
                ])
                ->get();

            return $response;
        }

        // 登入
        $isLogin = $this->srcLogin
            ->login($requestData['account'], $requestData['password']);

        if ($isLogin === false) {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('帳號或密碼錯誤')
                ->get();

            return $response;
        }

        // 取得Jwt Token
        $jwtToken = $this->srcLogin
            ->getJwtToken();

        if ($jwtToken === '') {
            $response = $this->toolResponseJson()
                ->setHttpCode(400)
                ->setMessage('登入失敗')
                ->get();

            return $response;
        }

        $data = [
            'jwtToken' => $jwtToken,
        ];

        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('登入成功')
            ->setData($data)
            ->get();

        return $response;
    }

    /**
     * 確認登入
     * 
     * @return JsonResponse
     */
    public function checkLogin(): JsonResponse
    {
        // 有確認登入中介層檢查後才會來這裡，所以直接回傳登入中資訊
        $response = $this->toolResponseJson()
            ->setHttpCode(200)
            ->setMessage('目前登入中')
            ->get();

        return $response;
    }
}
