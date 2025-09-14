<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\Register;
use App\Http\Controllers\Account\Login;
use App\Http\Controllers\Account\Info;
use App\Http\Controllers\Account\Logout;
use App\Http\Controllers\Account\Address;
use App\Http\Controllers\Banner\File;
use App\Http\Controllers\Banner\Banner;

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

        // 帳號地址相關
        Route::prefix('address')->group(function () {
            // 新增帳號地址
            Route::post('', [Address::class, 'create']);

            // 取得帳號地址列表
            Route::get('', [Address::class, 'getList']);

            // 刪除帳號地址
            Route::delete('{accountAddressId}', [Address::class, 'delete']);
        });
    });

    // 橫幅相關
    Route::prefix('banner')->group(function () {
        // 上傳橫幅圖片
        Route::post('photo', [File::class, 'uploadPhoto']);

        // 新增橫幅
        Route::post('', [Banner::class, 'create']);

        // 取得橫幅分頁
        Route::get('', [Banner::class, 'getPaged']);

        // 取得橫幅
        Route::get('{bannerId}', [Banner::class, 'get']);

        // 修改橫幅
        Route::put('{bannerId}', [Banner::class, 'modify']);
    });
});