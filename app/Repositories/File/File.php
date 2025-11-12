<?php

namespace App\Repositories\File;

use Illuminate\Database\Eloquent\Collection;
use App\Models\File as ModelFile;

/**
 * 資料存取層-檔案資訊
 */
class File
{
    /**
     * 新增檔案資訊
     * 
     * @param array $data 檔案資訊資料
     * 
     * @return ?ModelFile Model
     */
    public function insert(array $data): ?ModelFile
    {
        // 新增檔案資訊
        $model = new ModelFile();
        $model->name = $data['name'];
        $model->extension = $data['extension'];
        $model->type = $data['type'];
        $model->size = $data['size'];
        $model->path = $data['path'];
        $result = $model->save();

        // 新增失敗
        if ($result === false) {
            return null;
        }

        return $model;
    }

    /**
     * 透過檔案ID取得檔案資訊
     * 
     * @param array $fileIds 檔案ID
     * 
     * @return Collection 檔案資訊
     */
    public function findAllByFileIds(array $fileIds): Collection
    {
        return ModelFile::whereIn('file_id', $fileIds)->get();
    }
}