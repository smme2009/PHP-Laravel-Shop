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
                ->setHttpCode(422)
                ->setMessage('新增橫幅失敗，欄位填寫錯誤')
                ->bulkAddErrors($validationResult->errors)
                ->build();
        }

        // 加入管理員ID
        $authData = context()->get('authData');
        $data['adminId'] = $authData['id'];

        // 新增橫幅
        $model = $this->repoBanner->insert($data);

        // 新增失敗，回傳錯誤資料
        if ($model === null) {
            return $this->toolResult()
                ->setHttpCode(500)
                ->setMessage('新增橫幅失敗，系統異常')
                ->build();
        }

        // 取得檔案資訊
        $fileInfo = $this->toolFile()->getFileInfo($model->photo_file_id);

        // 新增成功，回傳橫幅資料
        return $this->toolResult()
            ->setHttpCode(200)
            ->setMessage('新增橫幅成功')
            ->bulkAddData($this->formatData($model))
            ->addData('photoUrl', $fileInfo->url)
            ->build();
    }

    /**
     * 取得橫幅分頁
     * 
     * @return OutputResult 處理結果
     */
    public function getPaged(): OutputResult
    {
        // 取得權限資料
        $authData = context()->get('authData');

        // 取得橫幅分頁
        $models = $this->repoBanner->findPagedByAdminId($authData['id']);

        // 取得檔案資訊
        $photoFileIds = $models->pluck('photo_file_id')->toArray();
        $fileInfos = $this->toolFile()->getFileInfos($photoFileIds);

        // 格式化橫幅分頁
        $data = $models
            // 格式化橫幅資料
            ->map(fn($item) => $this->formatData($item))
            // 設定商品圖片網址
            ->map(fn($item) => [
                ...$item,
                'photoUrl' => $fileInfos[$item['photoFileId']]->url,
            ])
            ->toArray();

        // 取得成功，回傳橫幅分頁
        return $this->toolResult()
            ->setHttpCode(200)
            ->setMessage('取得橫幅分頁成功')
            ->bulkAddData($data)
            ->build();
    }

    /**
     * 取得橫幅
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return OutputResult 處理結果
     */
    public function get(int $bannerId): OutputResult
    {
        // 取得權限資料
        $authData = context()->get('authData');

        // 取得橫幅Model
        $model = $this->repoBanner->findOneByBannerId($bannerId);

        // 取得橫幅失敗，回傳錯誤資料
        if ($model === null || $model->admin_id !== $authData['id']) {
            return $this->toolResult()
                ->setHttpCode(404)
                ->setMessage('取得橫幅失敗，橫幅不存在')
                ->build();
        }

        // 取得檔案資訊
        $fileInfo = $this->toolFile()->getFileInfo($model->photo_file_id);

        // 取得成功，回傳橫幅資料
        return $this->toolResult()
            ->setHttpCode(200)
            ->setMessage('取得橫幅成功')
            ->bulkAddData($this->formatData($model))
            ->addData('photoUrl', $fileInfo->url)
            ->build();
    }

    /**
     * 修改橫幅
     * 
     * @param int $bannerId 橫幅ID
     * @param array $data 橫幅資料
     * 
     * @return OutputResult 處理結果
     */
    public function modify(int $bannerId, array $data): OutputResult
    {
        // 取得權限資料
        $authData = context()->get('authData');

        // 取得橫幅Model
        $model = $this->repoBanner->findOneByBannerId($bannerId);

        // 取得橫幅失敗，回傳錯誤資料
        if ($model === null || $model->admin_id !== $authData['id']) {
            return $this->toolResult()
                ->setHttpCode(404)
                ->setMessage('修改橫幅失敗，橫幅不存在')
                ->build();
        }

        // 更新橫幅
        $model = $this->repoBanner->update($model, $data);

        // 更新失敗，回傳錯誤資料
        if ($model === null) {
            return $this->toolResult()
                ->setHttpCode(500)
                ->setMessage('修改橫幅失敗，系統異常')
                ->build();
        }

        // 取得檔案資訊
        $fileInfo = $this->toolFile()->getFileInfo($model->photo_file_id);

        // 更新成功，回傳橫幅資料
        return $this->toolResult()
            ->setHttpCode(200)
            ->setMessage('修改橫幅成功')
            ->bulkAddData($this->formatData($model))
            ->addData('photoUrl', $fileInfo->url)
            ->build();
    }

    /**
     * 移除橫幅
     * 
     * @param int $bannerId 橫幅ID
     * 
     * @return OutputResult 處理結果
     */
    public function remove(int $bannerId): OutputResult
    {
        // 取得權限資料
        $authData = context()->get('authData');

        // 取得橫幅Model
        $model = $this->repoBanner->findOneByBannerId($bannerId);

        // 取得橫幅失敗，回傳錯誤資料
        if ($model === null || $model->admin_id !== $authData['id']) {
            return $this->toolResult()
                ->setHttpCode(404)
                ->setMessage('移除橫幅失敗，橫幅不存在')
                ->build();
        }

        // 刪除橫幅
        $result = $this->repoBanner->delete($model);

        // 刪除失敗，回傳錯誤資料
        if ($result === false) {
            return $this->toolResult()
                ->setHttpCode(500)
                ->setMessage('移除橫幅失敗，系統異常')
                ->build();
        }

        // 刪除成功，回傳成功資料
        return $this->toolResult()
            ->setHttpCode(200)
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