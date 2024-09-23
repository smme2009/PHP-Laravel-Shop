<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Backend\Admin\Login;

use App\Http\Controllers\Api\Backend\Product\Product;
use App\Http\Controllers\Api\Backend\Product\ProductType;
use App\Http\Controllers\Api\Backend\Product\ProductStockType;
use App\Http\Controllers\Api\Backend\Product\ProductStock;
use App\Http\Controllers\Api\Backend\Banner\Banner;
use App\Http\Controllers\Api\Backend\Other\Editor;
use App\Http\Controllers\Api\Backend\Order\Order;

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

Route::middleware('accountAuth:admin')->group(function () {
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

    Route::controller(Banner::class)
        ->prefix('banner')
        ->group(function () {
            Route::get('', 'getBannerPage');
            Route::get('{bannerId}', 'getBanner')->whereNumber('bannerId');
            Route::post('photo', 'uploadBannerPhoto');
            Route::post('', 'addBanner');
            Route::put('{bannerId}', 'editBanner')->whereNumber('bannerId');
            Route::delete('{bannerId}', 'deleteBanner')->whereNumber('bannerId');
            Route::put('{bannerId}/status', 'editBannerStatus')->whereNumber('bannerId');
        });

    Route::post('editor/photo', [Editor::class, 'uploadEditorPhoto']);

    Route::controller(Order::class)
        ->prefix('order')
        ->group(function () {
            Route::get('', 'getOrderPage');
            Route::get('{orderId}', 'getOrder')->whereNumber('orderId');
        });
});
