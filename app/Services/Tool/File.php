<?php

namespace App\Services\Tool;

use Illuminate\Support\Facades\Storage;
use App\Models\File as ModelFile;
use App\Repositories\File\File as RepoFile;
use App\Services\Tool\Output\File as OutputFile;

/**
 * 檔案管理工具
 */
class File
{
    /**
     * 建構子
     * 
     * @param RepoFile $repoFile 資料存取層-檔案
     */
    public function __construct(
        private readonly RepoFile $repoFile
    ) {
    }

    /**
     * 上傳檔案
     * 
     * @param mixed $file 檔案
     * @param string $path 檔案路徑
     * 
     * @return ?OutputFile 檔案資訊
     */
    public function upload(mixed $file, string $path = ''): ?OutputFile
    {
        // 儲存檔案
        $publicPath = 'public' . ($path === '' ? '' : "/{$path}");
        $filePath = Storage::putFile($publicPath, $file);

        // 儲存失敗
        if (!$filePath) {
            return null;
        }

        // 新增檔案資料
        $model = $this->repoFile->insert([
            'name' => $file->getClientOriginalName(), // 檔案名稱
            'extension' => $file->getClientOriginalExtension(), // 副檔名
            'type' => $file->getMimeType(), // 檔案類型
            'size' => $file->getSize(), // 檔案大小
            'path' => $filePath, // 檔案路徑
        ]);

        // 新增失敗
        if ($model === null) {
            return null;
        }

        // 新增成功，回傳檔案資訊
        return $this->formatData($model);
    }

    /**
     * 取得檔案資訊
     * 
     * @param int $fileId 檔案ID
     * 
     * @return OutputFile 檔案資訊
     */
    public function getFileInfo(int $fileId): OutputFile
    {
        $fileInfos = $this->getFileInfos([$fileId]);
        return $fileInfos[$fileId];
    }

    /**
     * 批量取得檔案資訊
     * 
     * @param array $fileIds 檔案ID
     * 
     * @return OutputFile[] 檔案資訊
     */
    public function getFileInfos(array $fileIds): array
    {
        // 取得檔案資料
        $models = $this->repoFile->findAllByFileIds($fileIds);

        return $models
            // 格式化檔案資訊資料
            ->map(fn($model) => $this->formatData($model))
            // 將檔案ID設定為Key
            ->keyBy('fileId')
            ->toArray();
    }

    /**
     * 格式化檔案資訊資料
     * 
     * @param ModelFile $model Model
     * 
     * @return OutputFile 檔案資訊
     */
    private function formatData(ModelFile $model): OutputFile
    {
        // 生成檔案網址(有效期限1天)
        $url = Storage::temporaryUrl($model->path, now()->addDays(1));

        // 格式化資料
        return new OutputFile(
            fileId: $model->file_id,
            name: $model->name,
            extension: $model->extension,
            type: $model->type,
            size: $model->size,
            path: $model->path,
            url: $url,
        );
    }
}
