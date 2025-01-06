<?php

namespace App\Http\Service\Account;

use App\Http\Service\Service;
use App\Http\Repository\Account\Account as RepoAccount;
use App\Models\Account as ModelAccount;
use App\Tool\Validation\Validation as ToolValidation;
use App\Tool\Validation\Result;

// 帳號
class Account extends Service
{
    /**
     * 建構子
     */
    public function __construct(
        private RepoAccount $repoAccount,
    ) {
    }

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
            'account' => ['required', 'string', 'email', 'unique:App\Models\Account'],
            'password' => ['required', 'string'],
            'name' => ['required', 'string'],
        ];

        return ToolValidation::validate($data, $rule);
    }


    /**  
     * 新增帳號
     * 
     * @param int $roleId 角色ID
     * @param array $data 帳號資料
     * 
     * @return ?array 帳號資料
     */
    public function insert(int $roleId, array $data): ?array
    {
        $model = $this->repoAccount->insert($roleId, $data);

        if ($model === null) {
            return null;
        }

        return $this->getData($model);
    }

    /**
     * 取得帳號資料
     * 
     * @param ModelAccount $model 帳號Model
     * 
     * @return array 帳號資料
     */
    private function getData(ModelAccount $model): array
    {
        $data = [
            'accountId' => $model->account_id,
            'account' => $model->account,
            'name' => $model->name,
            'status' => $model->status,
            'createdAt' => $model->created_at,
            'updatedAt' => $model->updated_at,
        ];

        return $data;
    }
}
