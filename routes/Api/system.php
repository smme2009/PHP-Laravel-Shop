<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Health\Health;

// 健康檢查
Route::get('health', [Health::class, 'check']);