<?php

namespace App\Services\Banner;

use App\Services\Service;
use App\Services\Tool\Output\Result as OutputResult;

/**
 * 服務層-橫幅檔案
 */
class File extends Service
{
    /**
     * 上傳橫幅圖片
     *
     * @param mixed $photo 橫幅圖片
     *
     * @return OutputResult 處理結果
     */
    public function uploadPhoto(mixed $photo): OutputResult
    {
        // 取得驗證結果
        $validationResult = $this->toolValidator()
            ->addData('photo', $photo)
            ->addRule('photo', ['required', 'image', 'max:20480'])
            ->build();

        // 驗證失敗，回傳錯誤資料
        if ($validationResult->status === false) {
            return $this->toolResult()
                ->setHttpCode(400)
                ->setMessage('上傳橫幅圖片失敗，圖片格式不符')
                ->bulkAddData($validationResult->errors)
                ->build();
        }

        // 上傳檔案
        $fileInfo = $this->toolFile()->upload($photo, 'banner');

        // 上傳失敗，回傳錯誤資料
        if ($fileInfo === null) {
            return $this->toolResult()
                ->setHttpCode(500)
                ->setMessage('上傳橫幅圖片失敗，系統異常')
                ->build();
        }

        // 上傳成功，回傳檔案資料
        return $this->toolResult()
            ->setHttpCode(200)
            ->setMessage('上傳橫幅圖片成功')
            ->addData('photoFileId', $fileInfo->fileId)
            ->build();
    }
}