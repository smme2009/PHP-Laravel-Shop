<?php

namespace App\Services\Account;

use App\Enums\Role as EnumRole;
use App\Repositories\Account\Account as RepoAccount;
use App\Services\Service;
use App\Services\Tool\Validator as SrcToolValidator;
use App\Services\Tool\Result as SrcToolResult;

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
     * @return SrcToolResult
     */
    public function register($data): SrcToolResult
    {
        // 驗證註冊資料
        $validator = $this->getValidator($data);

        // 驗證失敗，回傳錯誤資料
        if ($validator->status === false) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('註冊失敗，欄位填寫錯誤')
                ->addDataList($validator->errorList);
        }

        // 驗證帳號是否已被使用
        $model = $this->repoAccount->findOneByAccount($data['account']);

        // 驗證失敗，回傳錯誤資料
        if ($model !== null) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('註冊失敗，帳號已被使用');
        }

        // 註冊帳號，並設定預設角色(買家)
        $model = $this->repoAccount->insertWithRole($data, EnumRole::BUYER->value);

        // 註冊失敗，回傳錯誤資料
        if ($model === null) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('註冊失敗，系統異常');
        }

        // 註冊成功，回傳帳號資料
        return $this->toolResult()
            ->setStatus(true)
            ->setMessage('註冊成功')
            ->addData('account', $model->account)
            ->addData('name', $model->name);
    }

    /**
     * 取得驗證器
     *
     * @param array $data 註冊資料
     * 
     * @return SrcToolValidator
     */
    private function getValidator($data): SrcToolValidator
    {
        $ruleList = [
            'account' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:8', 'max:12'],
            'name' => ['required', 'string'],
        ];

        return $this->toolValidator($data, $ruleList);
    }
}