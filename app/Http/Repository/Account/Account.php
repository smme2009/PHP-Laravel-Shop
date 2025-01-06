<?php

namespace App\Http\Repository\Account;

use Illuminate\Support\Facades\DB;
use App\Models\Account as ModelAccount;

// 帳號
class Account
{
    /**
     * 新增帳號
     * 
     * @param int $roleId 角色ID
     * @param array $data 帳號資料
     * 
     * @return ?ModelAccount
     */
    public function insert(int $roleId, array $data): ?ModelAccount
    {
        $model = new ModelAccount();

        DB::beginTransaction();

        // 儲存Model
        $isSave = $this->save($model, $data);

        if ($isSave === false) {
            return null;
        }

        // 新增角色
        $model->role()->attach($roleId);

        DB::commit();

        return $model;
    }

    /**
     * 儲存帳號Model
     * 
     * @param ModelAccount $model 帳號Model
     * @param array $data 帳號資料
     * 
     * @return bool 是否儲存成功
     */
    private function save(ModelAccount $model, array $data): bool
    {
        $model->account = $data['account'];
        $model->password = $data['password'];
        $model->name = $data['name'];
        $model->status = $data['status'];

        return $model->save();
    }
}
