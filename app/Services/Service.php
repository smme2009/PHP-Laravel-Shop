<?php

namespace App\Services;

use App\Services\Tool\Result as SrcToolResult;
use App\Services\Tool\ValidationResult as SrcToolValidationResult;
use App\Services\Tool\File as SrcToolFile;

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
     * 取得Service的通用驗證結果構建工具
     * 
     * @return SrcToolValidationResult
     */
    public function toolValidator(): SrcToolValidationResult
    {
        return new SrcToolValidationResult();
    }

    /**
     * 取得檔案管理工具
     * 
     * @return SrcToolFile
     */
    public function toolFile(): SrcToolFile
    {
        return app(SrcToolFile::class);
    }
}