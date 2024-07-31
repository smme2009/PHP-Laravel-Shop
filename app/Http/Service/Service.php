<?php

namespace App\Http\Service;

use App\Tool\Validation\Validation as ToolValidation;
use App\Tool\File\File as ToolFile;
use App\Tool\Jwt\Jwt as ToolJwt;

class Service
{
    /**
     * 驗證工具
     * 
     * @return ToolValidation
     */
    public function toolValidation()
    {
        $toolValidation = new ToolValidation();

        return $toolValidation;
    }

    /**
     * 檔案工具
     * 
     * @return ToolFile
     */
    public function toolFile()
    {
        $toolFile = new ToolFile();

        return $toolFile;
    }

    /**
     * Jwt工具
     * 
     * @return ToolJwt
     */
    public function toolJwt()
    {
        $toolJwt = new ToolJwt();

        return $toolJwt;
    }
}
