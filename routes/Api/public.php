<?php

use App\Http\Controllers\Api\Public\Product\ProductType as ProductType;

Route::get('product/type', [ProductType::class, 'getProductTypeList']);