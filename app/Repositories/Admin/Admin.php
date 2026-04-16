<?php

namespace App\Repositories\Admin;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Admin as ModelAdmin;

/**
 * 資料存取層-管理員
 */
class Admin
{
    /**
     * 透過帳號取得管理員資料
     * 
     * @param string $account 帳號
     * 
     * @return ?ModelAdmin 管理員Model
     */
    public function findOneByAccount(string $account): ?ModelAdmin
    {
        return ModelAdmin::where('account', $account)->first();
    }

    /**
     * 取得所有管理員資料
     * 
     * @return Collection 管理員資料
     */
    public function findAll(): Collection
    {
        return ModelAdmin::all();
    }

    /**
     * 新增管理員
     * 
     * @param array $data 管理員資料
     * 
     * @return ?ModelAdmin 管理員Model
     */
    public function insert(array $data): ?ModelAdmin
    {
        // 新增管理員
        $model = new ModelAdmin();
        $model->name = $data['name'];
        $model->account = $data['account'];
        $model->password = $data['password'];
        $model->status = $data['status'];
        $result = $model->save();

        // 新增失敗
        if ($result === false) {
            return null;
        }

        return $model;
    }
}