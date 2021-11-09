<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('product/detail/{id}', [App\Http\Controllers\ProductController::class, 'getDetail']);
Route::get('product/search', [App\Http\Controllers\ProductController::class, 'searchProduct']);
Route::get('product/get/{season}/{limit?}/{page?}', [App\Http\Controllers\ProductController::class, 'getProductBySeason']);

Route::post('login', [App\Http\Controllers\ApiController::class, 'authenticate']);
Route::post('register', [App\Http\Controllers\ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [App\Http\Controllers\ApiController::class, 'logout']);
    Route::get('get_user', [App\Http\Controllers\ApiController::class, 'get_user']);

    // Route::get('products', [App\Http\Controllers\ProductController::class, 'index']);
    // Route::get('products/{id}', [App\Http\Controllers\ProductController::class, 'show']);
    Route::post('products/create', [App\Http\Controllers\LoggedinController::class, 'store']);
    Route::put('products/update/{id}',  [App\Http\Controllers\LoggedinController::class, 'update']);
    Route::delete('products/delete/{id}',  [App\Http\Controllers\LoggedinController::class, 'destroy']);
});
