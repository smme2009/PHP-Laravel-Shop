<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Frontend\Banner\Banner;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('banner', [Banner::class, 'getBannerList']);