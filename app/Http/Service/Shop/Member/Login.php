<?php

namespace App\Http\Service\Shop\Member;

use App\Http\Service\Service;

use App\Tool\Validation\Validation as ToolValidation;
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
            'account' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];

        $result = ToolValidation::validate($data, $rule);

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
        $loginData = [
            'account' => $account,
            'password' => $password,
            'status' => 1, // 檢查帳號是否啟用
        ];

        $isLogin = auth('member')->attempt($loginData);

        return $isLogin;
    }

    /**
     * 取得Jwt Token
     * 
     * @return string
     */
    public function getJwtToken(): string
    {
        $userId = auth('member')->id();

        if (!$userId) {
            return '';
        }

        $data = [
            'accountId' => $userId,
        ];

        $jwtToken = $this->toolJwt()->encode($data);

        return $jwtToken;
    }
}
