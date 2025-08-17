<?php

namespace App\Repositories\Account;

use Illuminate\Database\Eloquent\Collection;
use App\Models\AccountAddress as ModelAccountAddress;

/**
 * 資料存取層-帳號地址
 */
class Address
{
    /**
     * 新增帳號地址
     * 
     * @param array $data 帳號地址資料
     * 
     * @return ?ModelAccountAddress
     */
    public function insert(array $data): ?ModelAccountAddress
    {
        // 新增帳號地址
        $model = new ModelAccountAddress();
        $model->account_id = $data['account_id'];
        $model->address = $data['address'];
        $result = $model->save();

        // 新增失敗
        if ($result === false) {
            return null;
        }

        return $model;
    }

    /**
     * 透過帳號地址ID取得地址資料
     * 
     * @param int $accountAddressId 帳號地址ID
     * 
     * @return ?ModelAccountAddress
     */
    public function findOneByAccountAddressId(int $accountAddressId): ?ModelAccountAddress
    {
        return ModelAccountAddress::find($accountAddressId);
    }

    /**
     * 透過帳號ID取得地址資料
     * 
     * @param int $accountId 帳號ID
     * 
     * @return Collection 地址資料
     */
    public function findAllByAccountid(int $accountId): Collection
    {
        return ModelAccountAddress::where('account_id', $accountId)->get();
    }

    /**
     * 刪除帳號地址
     * 
     * @param ModelAccountAddress $model 帳號地址Model
     * 
     * @return bool 是否刪除成功
     */
    public function delete(ModelAccountAddress $model): bool
    {
        return $model->delete();
    }
}