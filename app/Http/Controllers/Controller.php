<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use App\Services\Tool\Output\Result as SvcResult;
use App\Http\Controllers\Tool\Response as ToolResponse;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * 取得Controller的通用回應物件建構工具
     *
     * @return ToolResponse
     */
    protected function toolResponse(): ToolResponse
    {
        return new ToolResponse();
    }

    /**
     * 取得JSON回應
     * 
     * @param SvcResult $result
     * 
     * @return JsonResponse JSON回應
     */
    protected function getJsonResponse(SvcResult $result): JsonResponse
    {
        $responseData = [
            'message' => $result->message,
            'data' => $result->data,
        ];

        return response()->json($responseData, $result->httpCode);
    }
}
