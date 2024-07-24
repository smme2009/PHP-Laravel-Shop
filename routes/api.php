<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Backend\User\Login;

use App\Http\Controllers\Api\Backend\Product\Product;
use App\Http\Controllers\Api\Backend\Product\ProductType;
use App\Http\Controllers\Api\Backend\Product\ProductStockType;
use App\Http\Controllers\Api\Backend\Product\ProductStock;

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

    Route::prefix('product')->group(function () {
        Route::controller(Product::class)->group(function () {
            Route::get('', 'getProductPage');
            Route::get('{productId}', 'getProduct')->whereNumber('productId');
            Route::post('photo', 'uploadProductPhoto');
            Route::post('', 'addProduct');
            Route::put('{productId}', 'editProduct')->whereNumber('productId');
            Route::delete('{productId}', 'deleteProduct')->whereNumber('productId');
            Route::put('{productId}/status', 'editProductStatus')->whereNumber('productId');
        });

        Route::controller(ProductType::class)->prefix('type')->group(function () {
            Route::get('', 'getProductTypePage');
            Route::get('{productTypeId}', 'getProductType')->whereNumber('productTypeId');
            Route::post('', 'addProductType');
            Route::put('{productTypeId}', 'editProductType')->whereNumber('productTypeId');
            Route::delete('{productTypeId}', 'deleteProductType')->whereNumber('productTypeId');
            Route::put('{productTypeId}/status', 'editProductTypeStatus')->whereNumber('productTypeId');
        });

        Route::controller(ProductStock::class)
            ->prefix('{productId}/stock')
            ->whereNumber('productId')
            ->group(function () {
                Route::get('', 'getProductStockPage');
                Route::post('', 'addProductStock');
            });

        Route::prefix('stock')->group(function () {
            Route::get('type', [ProductStockType::class, 'getProductStockTypeList']);
        });
    });
});
