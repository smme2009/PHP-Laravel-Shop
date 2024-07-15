<?php

namespace App\Tool\File;

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
     * @return false|array
     */
    public function uploadFile(mixed $file, string $path = ''): false|array
    {
        $publicPath = 'public/' . $path;
        $filePath = Storage::putFile($publicPath, $file);

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

        $url = $this->getFileUrl($filePath);

        $fileInfo = [
            'fileId' => $model->file_id,
            'url' => $url,
        ];

        return $fileInfo;
    }

    /**
     * 取得檔案資訊
     * 
     * @param int $fileId 檔案ID
     * 
     * @return array 檔案資訊
     */
    public function getFileInfo(int $fileId): array
    {
        $fileData = ModelFile::where('file_id', $fileId)->first();

        $fileInfo = $this->setFileInfo($fileData);

        return $fileInfo;
    }

    /**
     * 取得檔案資訊列表
     * 
     * @param array $fileIdList 檔案ID列表
     * 
     * @return array 檔案資訊列表
     */
    public function getFileInfoList(array $fileIdList): array
    {
        $fileColl = ModelFile::whereIn('file_id', $fileIdList)->get();

        $fileInfoList = [];
        foreach ($fileColl as $fileData) {
            $fileInfoList[$fileData->file_id] = $this->setFileInfo($fileData);
        }

        return $fileInfoList;
    }

    /**
     * 設定檔案資訊
     * 
     * @param mixed $fileData 檔案資料
     * 
     * @return array 檔案資訊
     */
    private function setFileInfo(mixed $fileData): array
    {
        $url = $this->getFileUrl($fileData->path);

        $fileInfo = [
            'name' => $fileData->name,
            'exten' => $fileData->exten,
            'type' => $fileData->type,
            'size' => $fileData->size,
            'path' => $fileData->path,
            'url' => $url,
        ];

        return $fileInfo;
    }

    /**
     * 取得檔案網址
     * 
     * @param string $path 檔案路徑
     * 
     * @return string 檔案網址
     */
    private function getFileUrl(string $path): string
    {
        $url = Storage::url($path);
        $url = asset($url);

        return $url;
    }
}
