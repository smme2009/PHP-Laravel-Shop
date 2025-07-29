<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\Register;
use App\Http\Controllers\Account\Login;
use App\Http\Controllers\Account\Info;
use App\Http\Controllers\Account\Logout;

// 註冊
Route::post('register', [Register::class, 'register']);

// 登入
Route::post('login', [Login::class, 'login']);

// 帶有JWT Token驗證的路由
Route::middleware('accountAuth')->group(function () {
    // 登出
    Route::post('logout', [Logout::class, 'logout']);

    // 帳號相關
    Route::prefix('account')->group(function () {
        // 取得帳號資訊
        Route::get('profile', [Info::class, 'getProfile']);
    });
});