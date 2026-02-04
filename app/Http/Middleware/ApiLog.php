<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * 中介層-API紀錄
 */
class ApiLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 紀錄Log
        Log::channel('database')->info('API紀錄', [
            'uri' => $request->path(),
            'method' => $request->getMethod(),
        ]);

        // 接續動作
        return $next($request);
    }
}
