<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImagesMultipleController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProductController;
use App\Models\Product;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('product', ProductController::class);
    Route::apiResource('kategori', KategoriController::class);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::delete('deletImage/{id}', [ImagesMultipleController::class, 'destroy']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('addimages', [ImagesMultipleController::class, 'store']);
});

// Route::apiResource('product', ProductController::class);

Route::get('productUserget', [ProductController::class, 'index']);
Route::get('brandUser', [KategoriController::class, 'getuser']);
Route::get('productUser', [ProductController::class, 'getProductuser']);
Route::get('productDetailget/{id}', [ProductController::class, 'show']);
Route::post('login', [AuthController::class, 'userLogin']);
Route::get('/auth', [AuthController::class, 'index'])->name('login');
Route::post('/post', [ProductController::class, 'post']);
