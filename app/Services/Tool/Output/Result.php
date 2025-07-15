<?php

namespace App\Services\Tool\Output;

/**
 * Service的通用結果物件
 */
class Result
{
    /**
     * 建構子
     * 
     * @param bool $status 狀態
     * @param string $message 訊息
     * @param array $data 資料
     */
    public function __construct(
        public readonly bool $status, // 結果狀態
        public readonly string $message, // 訊息
        public readonly array $data, // 資料
    ) {
    }
}