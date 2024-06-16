<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use App\Tool\Response\Json as ToolResponseJson;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Response Json 工具
     * 
     * @return ToolResponseJson
     */
    public function toolResponseJson(): ToolResponseJson
    {
        $toolResponseJson = new ToolResponseJson();

        return $toolResponseJson;
    }
}
