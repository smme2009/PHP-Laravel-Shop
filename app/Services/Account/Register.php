<?php

namespace App\Services\Account;

use App\Enums\Role as EnumRole;
use App\Repositories\Account\Account as RepoAccount;
use App\Services\Service;
use App\Services\Tool\Output\Result as OutputResult;
use App\Services\Tool\Output\ValidationResult as OutputValidationResult;

/**
 * 服務層-帳號註冊
 */
class Register extends Service
{
    /**
     * 建構子
     *
     * @param RepoAccount $repoAccount 資料存取層-帳號
     */
    public function __construct(
        private readonly RepoAccount $repoAccount,
    ) {
    }

    /**
     * 註冊帳號
     *
     * @param array $data 註冊資料
     * 
     * @return OutputResult 處理結果
     */
    public function register(array $data): OutputResult
    {
        // 驗證註冊資料
        $validationResult = $this->getValidationResult($data);

        // 驗證失敗，回傳錯誤資料
        if ($validationResult->status === false) {
            return $this->toolResult()
                ->setHttpCode(422)
                ->setMessage('註冊失敗，欄位填寫錯誤')
                ->bulkAddErrors($validationResult->errors)
                ->build();
        }

        // 驗證帳號是否已被使用
        $model = $this->repoAccount->findOneByAccount($data['account']);

        // 驗證失敗，回傳錯誤資料
        if ($model !== null) {
            return $this->toolResult()
                ->setHttpCode(409)
                ->setMessage('註冊失敗，帳號已被使用')
                ->build();
        }

        // 註冊帳號，並設定預設角色(買家)
        $model = $this->repoAccount->insertWithRole($data, EnumRole::BUYER->value);

        // 註冊失敗，回傳錯誤資料
        if ($model === null) {
            return $this->toolResult()
                ->setHttpCode(500)
                ->setMessage('註冊失敗，系統異常')
                ->build();
        }

        // 註冊成功，回傳帳號資料
        return $this->toolResult()
            ->setHttpCode(200)
            ->setMessage('註冊成功')
            ->addData('account', $model->account)
            ->addData('name', $model->name)
            ->build();
    }

    /**
     * 取得驗證結果
     *
     * @param array $data 註冊資料
     * 
     * @return OutputValidationResult 驗證結果
     */
    private function getValidationResult(array $data): OutputValidationResult
    {
        return $this->toolValidator()
            ->bulkAddData($data)
            ->addRule('account', ['required', 'string', 'email'])
            ->addRule('password', ['required', 'string', 'min:8', 'max:12'])
            ->addRule('name', ['required', 'string'])
            ->build();
    }
}