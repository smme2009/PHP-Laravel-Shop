<?php

namespace App\Services\Banner;

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
            ->addData('bannerId', $model->banner_id)
            ->addData('photoFileId', (int) $model->photo_file_id)
            ->addData('name', $model->name)
            ->addData('url', $model->url ?? '')
            ->addData('startAt', $model->start_at)
            ->addData('endAt', $model->end_at)
            ->addData('sort', (int) $model->sort)
            ->addData('status', (bool) $model->status)
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
}