<?php

namespace App\Services\Account;

use App\Repositories\Account\Account as RepoAccount;
use App\Services\Service;
use App\Services\Tool\Output\Result as OutputResult;

/**
 * 服務層-帳號資訊
 */
class Info extends Service
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
     * 取得帳號資料
     * 
     * @param int $accountId 帳號ID
     * 
     * @return OutputResult 回傳結果
     */
    public function getProfile(int $accountId): OutputResult
    {
        // 取得帳號Model
        $model = $this->repoAccount->findOneByAccountId($accountId);

        // 查詢帳號失敗，回傳錯誤資料
        if ($model === null) {
            return $this->toolResult()
                ->setHttpCode(404)
                ->setMessage('取得帳號資料失敗，查無此帳號')
                ->build();
        }

        // 取得帳號資料成功，回傳帳號資料
        return $this->toolResult()
            ->setHttpCode(200)
            ->setMessage('取得帳號資料成功')
            ->addData('account', $model->account)
            ->addData('name', $model->name)
            ->build();
    }
}