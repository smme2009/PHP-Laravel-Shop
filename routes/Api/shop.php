<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Shop\Member\Register;
use App\Http\Controllers\Api\Shop\Member\Login;
use App\Http\Controllers\Api\Shop\Member\Address;
use App\Http\Controllers\Api\Shop\Banner\Banner;
use App\Http\Controllers\Api\Shop\Product\Product;
use App\Http\Controllers\Api\Shop\Cart\Cart;
use App\Http\Controllers\Api\Shop\Order\Order;
use App\Http\Controllers\Api\Shop\Order\OrderShip;
use App\Http\Controllers\Api\Shop\Order\OrderStatus;

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

    Route::prefix('order')
        ->group(function () {
            Route::controller(Order::class)
                ->group(function () {
                    Route::get('', 'getOrderPage');
                    Route::post('', 'addOrder');
                });

            Route::controller(OrderShip::class)
                ->prefix('ship')
                ->group(function () {
                    Route::get('', 'getOrderShipList');
                });

            Route::controller(OrderStatus::class)
                ->prefix('status')
                ->group(function () {
                    Route::get('', 'getOrderStatusList');
                });
        });
});