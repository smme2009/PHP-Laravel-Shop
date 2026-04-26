<?php

use Illuminate\Support\Facades\Route;
use App\Enums\Role as EnumRole;
use App\Http\Controllers\Admin\Login;
use App\Http\Controllers\Admin\Logout;
use App\Http\Controllers\Banner\File;
use App\Http\Controllers\Banner\Banner;

$role = EnumRole::Admin->value;

// 登入
Route::post('login', [Login::class, 'login']);

// 帶有JWT Token驗證的路由
Route::middleware('accountAuth:' . $role)->group(function () {
    // 登出
    Route::post('logout', [Logout::class, 'logout']);

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

        // 移除橫幅
        Route::delete('{bannerId}', [Banner::class, 'remove']);
    });
});
