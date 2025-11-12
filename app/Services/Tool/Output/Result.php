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
     * @param int $httpCode HTTP Code
     * @param string $message 訊息
     * @param array $data 資料
     */
    public function __construct(
        public readonly int $httpCode, // HTTP Code
        public readonly string $message, // 訊息
        public readonly array $data, // 資料
    ) {
    }
}