<?php

namespace App\Http\Controllers\Api\Backend\User;

use App\Http\Controllers\Controller;

use App\Http\Service\User\Login as SrcLogin;

use App\Tool\Response\Json as ToolResponseJson;

class Login extends Controller
{
    public function __construct(
        private SrcLogin $srcLogin,
    ) {
    }

    /**
     * 登入
     */
    public function login()
    {
        $requestData = [
            'account' => request()->get('account'),
            'password' => request()->get('password'),
        ];

        // 驗證資料
        $result = $this->srcLogin->validateData($requestData);

        if (!$result['status']) {
            $response = ToolResponseJson::init()
                ->setHttpCode(400)
                ->setMessage($result['errorMessage'])
                ->get();

            return $response;
        }

        // 登入
        $isLogin = $this->srcLogin->login($requestData['account'], $requestData['password']);

        if (!$isLogin) {
            $response = ToolResponseJson::init()
                ->setHttpCode(400)
                ->setMessage('帳號或密碼錯誤')
                ->get();

            return $response;
        }

        $response = ToolResponseJson::init()
            ->setHttpCode(200)
            ->setMessage('登入成功')
            ->get();

        return $response;
    }
}
