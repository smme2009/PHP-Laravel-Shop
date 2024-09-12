<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Frontend\Member\Register;
use App\Http\Controllers\Api\Frontend\Member\Login;
use App\Http\Controllers\Api\Frontend\Member\Address;
use App\Http\Controllers\Api\Frontend\Banner\Banner;
use App\Http\Controllers\Api\Frontend\Product\Product;
use App\Http\Controllers\Api\Frontend\Product\ProductType;
use App\Http\Controllers\Api\Frontend\Cart\Cart;

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

Route::middleware('accountAuth:member')->group(function () {
    Route::controller(Cart::class)
        ->prefix('cart')
        ->group(function () {
            Route::get('', 'getCartProductList');
            Route::put('', 'editCartProduct');
            Route::delete('', 'deleteCartProduct');
        });

    Route::prefix('member')->group(function () {
        Route::controller(Address::class)
            ->prefix('address')
            ->group(function () {
                Route::get('', 'getMemberAddressList');
                Route::post('', 'addMemberAddress');
            });
    });
});