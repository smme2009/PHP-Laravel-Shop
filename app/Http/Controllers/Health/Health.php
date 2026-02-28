<?php

namespace App\Http\Controllers\Health;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

/**
 * 控制層-健康檢查
 */
class Health extends Controller
{
    /**
     * 健康檢查
     *
     * @return Response
     */
    public function check(): Response
    {
        return response()->noContent();
    }
}