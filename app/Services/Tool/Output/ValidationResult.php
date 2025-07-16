<?php

namespace App\Services\Tool\Output;

/**
 * Service的通用驗證結果
 */
class ValidationResult
{
    /**
     * 建構子
     *
     * @param bool $status 狀態
     * @param array $errors 規則
     */
    public function __construct(
        public readonly bool $status, // 狀態
        public readonly array $errors, // 錯誤
    ) {
    }
}
