<?php

namespace App\Services\Tool;

/**
 * Service的通用驗證器
 */
class Validator
{
    /**
     * 驗證狀態
     * 
     * @var bool
     */
    public private(set) bool $status = false;

    /**
     * 錯誤列表
     *
     * @var array
     */
    public private(set) array $errorList = [];

    /**
     * 建構子
     *
     * @param array $data 驗證資料
     * @param array $rule 規則
     */
    public function __construct(array $data, array $rule)
    {
        // 驗證資料
        $validator = validator($data, $rule);

        $this->status = !$validator->fails();
        $this->errorList = $validator->errors()->toArray();
    }
}
