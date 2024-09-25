<?php

namespace App\Http\Service\Mgmt\Admin;

use App\Http\Service\Service;
use App\Tool\Validation\Result;

/**
 * 登入
 */
class Login extends Service
{
    /**  
     * 驗證資料
     * 
     * @param array $data 資料
     * 
     * @return Result 驗證結果
     */
    public function validateData(array $data): Result
    {
        // 驗證規則
        $rule = [
            'account' => ['required', 'string',],
            'password' => ['required', 'string'],
        ];

        $result = $this->toolValidation()
            ->validateData($data, $rule);

        return $result;
    }

    /**
     * 登入
     * 
     * @param string $account 帳號
     * @param string $password 密碼
     * 
     * @return bool 是否登入成功
     */
    public function login(string $account, string $password): bool
    {
        $loginData = [
            'account' => $account,
            'password' => $password,
            'status' => 1, // 檢查帳號是否啟用
        ];

        $isLogin = auth('admin')->attempt($loginData);

        return $isLogin;
    }

    /**
     * 取得Jwt Token
     * 
     * @return string JWT Token
     */
    public function getJwtToken(): string
    {
        $userId = auth('admin')->id();

        if (is_null($userId)) {
            return '';
        }

        $data = [
            'accountId' => $userId,
        ];

        $jwtToken = $this->toolJwt()
            ->encode($data);

        return $jwtToken;
    }
}
