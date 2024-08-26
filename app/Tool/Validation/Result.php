<?php

namespace App\Tool\Validation;

/**
 * 驗證結果
 */
class Result
{
    // 驗證狀態
    public bool $status;

    // 完整錯誤
    public array $error = [];

    // 訊息
    public array $message = [];

    /**
     * 建構子
     * 
     * @param bool $status 驗證狀態
     * @param array $error 錯誤
     */
    public function __construct(bool $status, array $error)
    {
        $this->status = $status;
        $this->setErrorData($error);
        $this->setMessage($error);
    }

    /**
     * 設定錯誤資料(完整)
     * 
     * @param array $error 錯誤
     * 
     * @return void
     */
    private function setErrorData(array $error)
    {
        foreach ($error as $name => $message) {
            $this->error[] = [
                'name' => $name,
                'message' => $message,
            ];
        }
    }

    /**
     * 設定錯誤訊息
     * 
     * @param array $error 錯誤
     * 
     * @return void
     */
    private function setMessage(array $error)
    {
        foreach ($error as $message) {
            $this->message = array_merge($this->message, $message);
        }
    }
}
