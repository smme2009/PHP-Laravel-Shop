<?php

namespace App\Services\Banner;

use App\Models\Banner as ModelBanner;
use App\Repositories\Banner\Banner as RepoBanner;
use App\Services\Service;
use App\Services\Tool\Output\Result as OutputResult;
use App\Services\Tool\Output\ValidationResult as OutputValidationResult;

/**
 * 服務層-橫幅
 */
class Banner extends Service
{
    /**
     * 建構子
     * 
     * @param RepoBanner $repoBanner 資料存取層-橫幅
     */
    public function __construct(
        private readonly RepoBanner $repoBanner,
    ) {
    }

    /**
     * 新增橫幅
     * 
     * @param array $data 橫幅資料
     * 
     * @return OutputResult 處理結果
     */
    public function create(array $data): OutputResult
    {
        // 驗證新增資料
        $validationResult = $this->getValidationResult($data);

        // 驗證失敗，回傳錯誤資料
        if ($validationResult->status === false) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('新增橫幅失敗，欄位填寫錯誤')
                ->bulkAddData($validationResult->errors)
                ->build();
        }

        // 新增橫幅
        $model = $this->repoBanner->insert($data);

        // 新增失敗，回傳錯誤資料
        if ($model === null) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('新增橫幅失敗，系統異常')
                ->build();
        }

        // 新增成功，回傳橫幅資料
        return $this->toolResult()
            ->setStatus(true)
            ->setMessage('新增橫幅成功')
            ->bulkAddData($this->formatData($model))
            ->build();
    }

    /**
     * 取得橫幅分頁
     * 
     * @param int $accountId 帳號ID
     * 
     * @return OutputResult 處理結果
     */
    public function getPaged(int $accountId): OutputResult
    {
        // 取得橫幅分頁
        $models = $this->repoBanner->findPagedByAccountId($accountId);

        // 格式化橫幅分頁
        $data = $models
            ->map(fn($item) => $this->formatData($item))
            ->toArray();

        // 取得成功，回傳橫幅分頁
        return $this->toolResult()
            ->setStatus(true)
            ->setMessage('取得橫幅分頁成功')
            ->bulkAddData($data)
            ->build();
    }

    /**
     * 取得橫幅
     * 
     * @param int $accountId 帳號ID
     * @param int $bannerId 橫幅ID
     * 
     * @return OutputResult 處理結果
     */
    public function get(int $accountId, int $bannerId): OutputResult
    {
        // 取得橫幅Model
        $model = $this->repoBanner->findOneByBannerId($bannerId);

        // 取得橫幅失敗，回傳錯誤資料
        if ($model === null || $model->account_id !== $accountId) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('取得橫幅失敗，橫幅不存在')
                ->build();
        }

        // 取得成功，回傳橫幅資料
        return $this->toolResult()
            ->setStatus(true)
            ->setMessage('取得橫幅成功')
            ->bulkAddData($this->formatData($model))
            ->build();
    }

    /**
     * 修改橫幅
     * 
     * @param int $accountId 帳號ID
     * @param int $bannerId 橫幅ID
     * @param array $data 橫幅資料
     * 
     * @return OutputResult 處理結果
     */
    public function modify(int $accountId, int $bannerId, array $data): OutputResult
    {
        // 取得橫幅Model
        $model = $this->repoBanner->findOneByBannerId($bannerId);

        // 取得橫幅失敗，回傳錯誤資料
        if ($model === null || $model->account_id !== $accountId) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('修改橫幅失敗，橫幅不存在')
                ->build();
        }

        // 更新橫幅
        $model = $this->repoBanner->update($model, $data);

        // 修改失敗，回傳錯誤資料
        if ($model === null) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('修改橫幅失敗，系統異常')
                ->build();
        }

        // 修改成功，回傳橫幅資料
        return $this->toolResult()
            ->setStatus(true)
            ->setMessage('修改橫幅成功')
            ->bulkAddData($this->formatData($model))
            ->build();
    }

    /**
     * 移除橫幅
     * 
     * @param int $accountId 帳號ID
     * @param int $bannerId 橫幅ID
     * 
     * @return OutputResult 處理結果
     */
    public function remove(int $accountId, int $bannerId): OutputResult
    {
        // 取得橫幅Model
        $model = $this->repoBanner->findOneByBannerId($bannerId);

        // 取得橫幅失敗，回傳錯誤資料
        if ($model === null || $model->account_id !== $accountId) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('移除橫幅失敗，橫幅不存在')
                ->build();
        }

        // 刪除橫幅
        $result = $this->repoBanner->delete($model);

        // 刪除失敗，回傳錯誤資料
        if ($result === false) {
            return $this->toolResult()
                ->setStatus(false)
                ->setMessage('移除橫幅失敗，系統異常')
                ->build();
        }

        // 刪除成功，回傳成功資料
        return $this->toolResult()
            ->setStatus(true)
            ->setMessage('移除橫幅成功')
            ->build();
    }

    /**
     * 取得驗證結果
     *
     * @param array $data 橫幅資料
     * 
     * @return OutputValidationResult 驗證結果
     */
    private function getValidationResult(array $data): OutputValidationResult
    {
        return $this->toolValidator()
            ->bulkAddData($data)
            ->addRule('name', ['required', 'string'])
            ->addRule('photoFileId', ['required', 'integer'])
            ->addRule('url', ['nullable', 'url'])
            ->addRule('startAt', ['required', 'date'])
            ->addRule('endAt', ['required', 'date'])
            ->addRule('sort', ['required', 'integer', 'min:1', 'max:100'])
            ->addRule('status', ['required', 'boolean'])
            ->build();
    }

    /**
     * 格式化橫幅資料
     * 
     * @param ModelBanner $model Model
     * 
     * @return array 格式化後的橫幅資料
     */
    private function formatData(ModelBanner $model): array
    {
        return [
            'bannerId' => $model->banner_id,
            'photoFileId' => (int) $model->photo_file_id,
            'name' => $model->name,
            'url' => $model->url ?? '',
            'startAt' => $model->start_at,
            'endAt' => $model->end_at,
            'sort' => (int) $model->sort,
            'status' => (bool) $model->status,
        ];
    }
}