<?php

namespace App\Services;

use App\Services\Tool\Result as SrcToolResult;

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
}