<?php

namespace App\Tool;

use Illuminate\Support\Facades\Storage;

use App\Models\File as ModelFile;

/**
 * 檔案
 */
class File
{
    /**
     * 上傳檔案
     * 
     * @param mixed $file 檔案
     * @param string $path 檔案路徑
     * 
     * @return array|false
     */
    public static function uploadFile(mixed $file, string $path = ''): array|false
    {
        $filePath = Storage::putFile($path, $file);

        if (!$filePath) {
            return false;
        }

        $model = new ModelFile();

        $model->name = $file->getClientOriginalName(); // 檔案名稱
        $model->extension = $file->getClientOriginalExtension(); // 副檔名
        $model->type = $file->getMimeType(); // 檔案類型
        $model->size = $file->getSize(); // 檔案大小
        $model->path = $filePath; // 路徑

        $isSave = $model->save();

        if (!$isSave) {
            return false;
        }

        $fileInfo = [
            'fileId' => $model->file_id,
            'path' => $filePath,
        ];

        return $fileInfo;
    }
}
