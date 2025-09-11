<?php

namespace App\Services\Tool\Output;

/**
 * 檔案資訊物件
 */
class File
{
    /**
     * 建構子
     * 
     * @param int $fileId // 檔案ID
     * @param string $name // 檔案名稱
     * @param string $extension // 檔案副檔名
     * @param string $type // 檔案類型
     * @param int $size // 檔案大小
     * @param string $path // 檔案路徑
     * @param string $url // 檔案網址
     */
    public function __construct(
        public readonly int $fileId, // 檔案ID
        public readonly string $name, // 檔案名稱
        public readonly string $extension, // 檔案副檔名
        public readonly string $type, // 檔案類型
        public readonly int $size, // 檔案大小
        public readonly string $path, // 檔案路徑
        public readonly string $url, // 檔案網址
    ) {
    }
}