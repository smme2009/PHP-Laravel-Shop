<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Frontend\Member\Register;
use App\Http\Controllers\Api\Frontend\Member\Login;
use App\Http\Controllers\Api\Frontend\Banner\Banner;
use App\Http\Controllers\Api\Frontend\Product\Product;
use App\Http\Controllers\Api\Frontend\Product\ProductType;

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

Route::post('register', [Register::class, 'registerMember']);
Route::post('login', [Login::class, 'login']);

Route::get('banner', [Banner::class, 'getBannerList']);

Route::controller(Product::class)
    ->prefix('product')
    ->group(function () {
        Route::get('', 'getProductPage');
        Route::get('{productId}', 'getProduct')->whereNumber('productId');
    });

Route::get('product/type', [ProductType::class, 'getProductTypeList']);