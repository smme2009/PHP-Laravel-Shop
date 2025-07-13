<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Account\Register;

Route::post('register', [Register::class, 'register']);