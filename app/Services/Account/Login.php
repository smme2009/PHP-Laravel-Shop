<?php

namespace App\Services\Account;

use App\Services\Service;
use App\Services\Tool\Jwt;
use App\Services\Tool\Output\Result as OutputResult;
use App\Services\Tool\Output\ValidationResult as OutputValidationResult;

/**
 * 服務層-帳號登入
 */
class Login extends Service
{
    /**
     * 建構子
     * @param Jwt $toolJwt 工具-JWT Token
     */
    public function __construct(
        private readonly Jwt $toolJwt,
    ) {

    }

    /**
     * 登入帳號
     * 
     * @param string $account 帳號
     * @param string $password 密碼
     * 
     * @return OutputResult 處理結果
     */
    public function login(string $account, string $password): OutputResult
    {
        // 驗證登入資料
        $validationResult = $this->getValidationResult($account, $password);

        // 驗證失敗，回傳錯誤資料
        if ($validationResult->status === false) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('登入失敗，欄位填寫錯誤')
                ->bulkAddData($validationResult->errors)
                ->build();
        }

        // 驗證登入身份
        $authPass = auth()->once(['account' => $account, 'password' => $password]);

        // 驗證失敗，回傳錯誤資料
        if ($authPass === false) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('登入失敗，帳號或密碼錯誤')
                ->build();
        }

        // 取得JWT Token
        $jwtToken = $this->getJwtToken();

        // 取得JWT Token失敗，回傳錯誤資料
        if ($jwtToken === '') {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('登入失敗，取得JWT Token時發生異常')
                ->build();
        }

        // 登入成功，回傳JWT Token
        return $this->toolResult()
            ->setStatus(true)
            ->setMessage('登入成功')
            ->addData('jwtToken', $jwtToken)
            ->build();
    }

    /**
     * 取得驗證結果
     * 
     * @param string $account 帳號
     * @param string $password 密碼
     * 
     * @return OutputValidationResult 驗證結果物件，包含驗證狀態和錯誤訊息
     */
    private function getValidationResult(string $account, string $password): OutputValidationResult
    {
        return $this->toolValidator()
            ->addData('account', $account)
            ->addData('password', $password)
            ->addRule('account', ['required', 'string', 'email'])
            ->addRule('password', ['required', 'string', 'min:8', 'max:12'])
            ->build();
    }

    /**
     * 取得 JWT Token
     *
     * @return string JWT Token
     */
    private function getJwtToken(): string
    {
        // 取得帳號Model
        $model = auth()->user();

        // 取得帳號角色
        $roleIds = $model->role
            ->pluck('role_id')
            ->toArray();

        // 設定JWT Token資料
        $data = [
            'accountId' => $model->account_id,
            'roleIds' => $roleIds,
        ];

        // 編碼JWT Token
        return $this->toolJwt->encode($data);
    }
}