<?php

namespace App\Tool\Validation;

/**
 * 驗證結果
 */
class Result
{
    /**
     * 建構子
     * 
     * @param bool $status 驗證狀態
     * @param array $errorList 錯誤列表
     */
    public function __construct(
        // 驗證狀態
        private bool $status,

        // 錯誤列表
        private array $errorList,
    ) {
    }

    /**
     * 魔術方法(get)
     * 
     * @param mixed $name 屬性名稱
     * 
     * @return mixed 值
     */
    public function __get($name): mixed
    {
        return $this->{$name};
    }
}
