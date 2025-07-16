<?php

namespace App\Services\Tool;

use App\Services\Tool\Output\Result as OutputResult;

/**
 * Service的通用結果物件建構工具
 */
class Result
{
    /**
     * 結果狀態
     * @var bool
     */
    private bool $status = false;

    /**
     * 訊息
     * @var string
     */
    private string $message = '';

    /**
     * 資料
     * @var array
     */
    private array $data = [];

    /**
     * 設定結果狀態
     * 
     * @param bool $status 狀態
     * 
     * @return Result
     */
    public function setStatus(bool $status): self
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
    public function setMessage(string $message): self
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
    public function addData(string $name, mixed $value): self
    {
        $this->data[$name] = $value;
        return $this;
    }

    /**
     * 批量新增資料
     *
     * @param array $data 資料
     * 
     * @return Result
     */
    public function bulkAddData(array $data): self
    {
        foreach ($data as $key => $value) {
            $this->addData($key, $value);
        }

        return $this;
    }

    /**
     * 取得結果物件
     * 
     * @return OutputResult
     */
    public function build(): OutputResult
    {
        return new OutputResult(
            status: $this->status,
            message: $this->message,
            data: $this->data
        );
    }
}