<?php

namespace App\Services\Account;

use App\Models\AccountAddress as ModelAccountAddress;
use App\Repositories\Account\Address as RepoAddress;
use App\Services\Service;
use App\Services\Tool\Output\Result as OutputResult;
use App\Services\Tool\Output\ValidationResult as OutputValidationResult;

/**
 * 服務層-帳號地址
 */
class Address extends Service
{
    /**
     * 建構子
     * 
     * @param RepoAddress $repoAddress 資料存取層-帳號地址
     */
    public function __construct(
        private readonly RepoAddress $repoAddress,
    ) {
    }

    /**
     * 新增帳號地址
     * 
     * @param array $data 帳號地址資料
     * 
     * @return OutputResult 處理結果
     */
    public function create(array $data): OutputResult
    {
        // 驗證帳號地址資料
        $validationResult = $this->getValidationResult($data);

        // 驗證失敗，回傳錯誤資料
        if ($validationResult->status === false) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('新增失敗，欄位填寫錯誤')
                ->bulkAddData($validationResult->errors)
                ->build();
        }

        // 新增帳號地址
        $model = $this->repoAddress->insert($data);

        // 新增失敗，回傳錯誤資料
        if ($model === null) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('新增地址失敗，系統異常')
                ->build();
        }

        // 回傳結果
        return $this->toolResult()
            ->setStatus(true)
            ->setMessage('新增地址成功')
            ->bulkAddData($this->formatData($model))
            ->build();
    }

    /**
     * 取得帳號地址列表
     * 
     * @param int $accountId 帳號ID
     * 
     * @return OutputResult 處理結果
     */
    public function getList(int $accountId): OutputResult
    {
        // 取得帳號地址Model
        $models = $this->repoAddress->findAllByAccountid($accountId);

        // 格式化帳號地址資料
        $addresses = $models
            ->map(fn($item) => $this->formatData($item))
            ->toArray();

        // 回傳結果
        return $this->toolResult()
            ->setStatus(true)
            ->setMessage('取得帳號地址列表成功')
            ->bulkAddData($addresses)
            ->build();
    }

    /**
     * 刪除帳號地址
     * 
     * @param int $accountId 帳號ID
     * @param int $accountAddressId 帳號地址ID
     * 
     * @return OutputResult 處理結果
     */
    public function delete(int $accountId, int $accountAddressId): OutputResult
    {
        // 取得帳號地址Model
        $model = $this->repoAddress->findOneByAccountAddressId($accountAddressId);

        // 取得資料失敗，回傳錯誤資料
        if ($model === null || $model->account_id !== $accountId) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('刪除地址失敗，查無資料')
                ->build();
        }

        // 刪除帳號地址
        $result = $this->repoAddress->delete($model);

        // 刪除失敗，回傳錯誤資料
        if ($result === false) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('刪除地址失敗，系統異常')
                ->build();
        }

        // 刪除成功，回傳成功資料
        return $this->toolResult()
            ->setStatus(true)
            ->setMessage('刪除地址成功')
            ->build();
    }

    /**
     * 格式化帳號地址資料
     * 
     * @param ModelAccountAddress $model Model
     * 
     * @return array 格式化後的帳號地址資料
     */
    private function formatData(ModelAccountAddress $model): array
    {
        return [
            'accountAddressId' => $model->account_address_id,
            'address' => $model->address,
        ];
    }

    /**
     * 取得驗證結果
     *
     * @param array $data 帳號地址資料
     * 
     * @return OutputValidationResult 驗證結果
     */
    private function getValidationResult(array $data): OutputValidationResult
    {
        return $this->toolValidator()
            ->bulkAddData($data)
            ->addRule('address', ['required', 'string'])
            ->build();
    }
}