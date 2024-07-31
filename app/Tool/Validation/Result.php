<?php

namespace App\Tool\Validation;

/**
 * 驗證結果
 */
class Result
{
    // 驗證狀態
    public bool $status;

    // 訊息
    public array $message;

    /**
     * 建構子
     * 
     * @param bool $status 驗證狀態
     * @param array $message 訊息
     */
    public function __construct(bool $status, array $message)
    {
        $this->status = $status;
        $this->message = $message;
    }
}
