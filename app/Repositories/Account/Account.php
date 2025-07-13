<?php

namespace App\Repositories\Account;

use App\Enums\Status as EnumStatus;
use App\Models\Account as ModelAccount;

/**
 * 資料存取層-帳號
 */
class Account
{
    /**
     * 透過帳號取得帳號資料
     * 
     * @param string $account 帳號
     * 
     * @return ?ModelAccount
     */
    public function findOneByAccount(string $account): ?ModelAccount
    {
        return ModelAccount::where('account', operator: $account)->first();
    }

    /**
     * 新增帳號並設定角色
     *
     * @param array $data 帳號資料
     * @param int $roleId 角色ID
     * 
     * @return ?ModelAccount
     */
    public function insertWithRole(array $data, int $roleId): ?ModelAccount
    {
        // 新增帳號
        $model = new ModelAccount();
        $model->account = $data['account'];
        $model->password = $data['password'];
        $model->name = $data['name'];
        $model->status = EnumStatus::ENABLED->value;
        $result = $model->save();

        // 新增失敗
        if ($result === false) {
            return null;
        }

        // 新增帳號的角色
        $model->role()->attach($roleId);

        return $model;
    }
}