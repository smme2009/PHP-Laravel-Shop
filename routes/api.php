<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Backend\User\Login;
use App\Http\Controllers\Api\Backend\Product\Product;

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

Route::post('login', [Login::class, 'login']);

Route::middleware('userAuth')->group(function () {
    Route::get('check-login', [Login::class, 'checkLogin']);

    Route::controller(Product::class)->prefix('product')->group(function () {
        Route::get('', 'getProductPage');
        Route::get('{productId}', 'getProduct')->whereNumber('productId');
        Route::post('photo', 'uploadProductPhoto');
        Route::post('', 'addProduct');
    });
});
