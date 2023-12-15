<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/get/products',[ProductController::class, 'index']);
Route::get('/get/product/{id}',[ProductController::class, 'get_product_byId']);
// Route::post('/add/brand',[BrandController::class, 'add_brand']);
// Route::post('/update/brand',[BrandController::class, 'upadte_brand_data']);
Route::delete('/delete/product/{id}',[ProductController::class, 'delete_product']);

