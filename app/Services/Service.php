<?php

namespace App\Services;

use App\Services\Tool\Result as SrcToolResult;
use App\Services\Tool\Validator as SrcToolValidator;

/**
 * Service通用層，可以用來存放共用的工具和方法
 */
class Service
{
    /**
     * 取得Service的通用結果物件建構工具
     *
     * @return SrcToolResult
     */
    public function toolResult(): SrcToolResult
    {
        return new SrcToolResult();
    }

    /**
     * 取得Service的通用驗證器
     *
     * @param array $data 驗證資料
     * @param array $rule 規則
     * 
     * @return SrcToolValidator
     */
    public function toolValidator(array $data, array $rule): SrcToolValidator
    {
        return new SrcToolValidator($data, $rule);
    }
}