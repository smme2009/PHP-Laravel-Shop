<?php

namespace App\Http\Service\User;

use App\Tool\ValidateData as ToolValidateData;
use App\Tool\Jwt as ToolJwt;

/**
 * 登入
 */
class Login
{
    /**  
     * 驗證資料
     * 
     * @param array $data 資料
     * 
     * @return array
     */
    public function validateData(array $data): array
    {
        // 驗證規則
        $rule = [
            'account' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];

        $result = ToolValidateData::validateData($data, $rule);

        return $result;
    }

    /**
     * 登入
     * 
     * @param string $account 帳號
     * @param string $password 密碼
     * 
     * @return bool
     */
    public function login(string $account, string $password): bool
    {
        $loginData =  [
            'email' => $account,
            'password' => $password,
            'status' => 1, // 檢查帳號是否啟用
        ];

        $isLogin = auth()->attempt($loginData);

        return $isLogin;
    }

    /**
     * 取得Jwt Token
     * 
     * @return string
     */
    public function getJwtToken(): string
    {
        $userId = auth()->id();

        if (!$userId) {
            return '';
        }

        $data = [
            'userId' => $userId,
        ];

        $jwtToken = ToolJwt::encode($data);

        return $jwtToken;
    }
}
