<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\Register;
use App\Http\Controllers\Account\Login;

// 註冊
Route::post('register', [Register::class, 'register']);

// 登入
Route::post('login', [Login::class, 'login']);