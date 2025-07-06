<?php

namespace App\Services\Tool;

/**
 * Service的通用結果物件建構工具
 */
class Result
{
    /**
     * 結果狀態
     * @var bool
     */
    public private(set) bool $status = false;

    /**
     * 訊息
     * @var string
     */
    public private(set) string $message = '';

    /**
     * 資料
     * @var array
     */
    public private(set) array $data = [];

    /**
     * 設定結果狀態
     * 
     * @param bool $status 狀態
     * 
     * @return Result
     */
    public function setStatus(bool $status): Result
    {
        $this->status = $status;
        return $this;
    }

    /**
     * 設定訊息
     * 
     * @param string $message 訊息
     * 
     * @return Result
     */
    public function setMessage(string $message): Result
    {
        $this->message = $message;
        return $this;
    }

    /**
     * 新增資料
     * 
     * @param string $name 名稱
     * @param mixed $value 值
     * 
     * @return Result
     */
    public function addData(string $name, mixed $value): Result
    {
        $this->data[$name] = $value;
        return $this;
    }
}