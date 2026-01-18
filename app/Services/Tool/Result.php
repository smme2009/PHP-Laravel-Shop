<?php

namespace App\Services\Tool;

use App\Services\Tool\Output\Result as OutputResult;

/**
 * Service的通用結果物件建構工具
 */
class Result
{
    /**
     * HTTP Code
     * @var int
     */
    private int $httpCode = 0;

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
     * 錯誤資料
     * @var array
     */
    private array $errors = [];

    /**
     * 設定HTTP Code
     * 
     * @param int $httpCode HTTP Code
     * 
     * @return self
     */
    public function setHttpCode(int $httpCode): self
    {
        $this->httpCode = $httpCode;
        return $this;
    }

    /**
     * 設定訊息
     * 
     * @param string $message 訊息
     * 
     * @return self
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
     * @return self
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
     * @return self
     */
    public function bulkAddData(array $data): self
    {
        foreach ($data as $key => $value) {
            $this->addData($key, $value);
        }

        return $this;
    }

    /**
     * 新增錯誤資料
     * 
     * @param string $name 名稱
     * @param mixed $value 值
     * 
     * @return self
     */
    public function addError(string $name, mixed $value): self
    {
        $this->errors[$name] = $value;
        return $this;
    }

    /**
     * 批量新增錯誤資料
     *
     * @param array $errors 錯誤資料
     * 
     * @return self
     */
    public function bulkAddErrors(array $errors): self
    {
        foreach ($errors as $key => $value) {
            $this->addError($key, $value);
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
            httpCode: $this->httpCode,
            message: $this->message,
            data: $this->data,
            errors: $this->errors,
        );
    }
}