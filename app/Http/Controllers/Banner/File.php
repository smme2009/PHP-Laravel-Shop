<?php

namespace App\Http\Controllers\Banner;

use Illuminate\Http\JsonResponse;
use App\Services\Banner\File as SrcFile;
use App\Http\Controllers\Controller;

/**
 * 控制層-橫幅檔案
 */
class File extends Controller
{
    /**
     * 建構子
     * 
     * @param SrcFile $srcFile 服務層-橫幅檔案
     */
    public function __construct(
        private readonly SrcFile $srcFile,
    ) {
    }

    /**
     * 上傳橫幅圖片
     * 
     * @return JsonResponse
     */
    public function uploadPhoto(): JsonResponse
    {
        // 取得請求圖片
        $file = request()->file('photo');

        // 上傳圖片
        $result = $this->srcFile->uploadPhoto($file);

        // 回傳結果
        return $this->toolResponse()
            ->setHttpCodeByStatus($result->status)
            ->setMessage($result->message)
            ->bulkAddData($result->data)
            ->build();
    }
}