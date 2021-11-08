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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('product/detail/{id}', [App\Http\Controllers\ProductController::class, 'getDetail']);
Route::get('product/search', [App\Http\Controllers\ProductController::class, 'searchProduct']);
Route::get('product/get/{season}/{limit?}/{page?}', [App\Http\Controllers\ProductController::class, 'getProductBySeason']);
asdas