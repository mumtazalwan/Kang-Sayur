<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// controller
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\Seller\TokoController;
use App\Http\Controllers\Seller\ProdukController;
use App\Http\Controllers\LogVisitorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SaleController;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/user/register', [AuthenticationController::class, 'registerAsUser']);
    Route::post('/seller/register', [AuthenticationController::class, 'registerAsSeller']);
    Route::post('/driver/register', [AuthenticationController::class, 'registerAsDriver']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
});

// user
Route::group(['middleware' => ['role:user', 'auth:sanctum'], 'prefix' => 'user'], function () {
    Route::get('/profile', [UserController::class, 'index']);

    Route::get('/toko', [TokoController::class, 'index']);
    Route::get('/toko/detail', [TokoController::class, 'detail_toko']);
    Route::get('/produk', [ProdukController::class, 'produk']);

    Route::get('/toko/terderkat', [TokoController::class, 'getNearestStore']);
    Route::get('/toko/popular', [LogVisitorController::class, 'mostPopularStore']);

    Route::get('/produk/cart', [CartController::class, 'listCart']);
    Route::get('/produk/cart/add', [CartController::class, 'addToChart']);
    Route::get('/produk/cart/minus', [CartController::class, 'minus']);
    Route::get('/produk/cart/delete', [CartController::class, 'deleteAll']);
    Route::get('/produk/cart/custom', [CartController::class, 'custom']);
    Route::post('/produk/cart/pesan', [OrderController::class, 'store']);

    Route::get('/produk/home/search/{keyword}', [ProdukController::class, 'home_search']);
    Route::get('/produk/sale', [SaleController::class, 'index']);
    Route::get('/produk/populer', [LogVisitorController::class, 'getProductPopuler']);
    Route::get('/produk/sering-dikunjungi', [LogVisitorController::class, 'getUserMostVisitor']);
    Route::get('/produk/detail', [ProdukController::class, 'detail_produk']);

    Route::get('/produk/kategori', [ProdukController::class, 'Categories']);
    Route::get('/produk/kategori/item', [ProdukController::class, 'produkByCategory']);
});

// seller
Route::group(['middleware' => ['role:seller', 'auth:sanctum'], 'prefix' => 'seller'], function () {
    Route::post('/produk/create', [ProdukController::class, 'create']);
    Route::get('/analysis', [TokoController::class, 'analysis']);

    Route::group(['prefix' => '/produk/display'], function () {
        Route::get('', [ProdukController::class, 'listProduct']);
        Route::get('/verify', [ProdukController::class, 'onVerify']);
    });

    Route::group(['prefix' => '/status/product'], function () {
        Route::get('/confirmed', [OrderController::class, 'confirm']);
        Route::get('/prepared', [ProdukController::class, ' ']);
    });
});

// admin
Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
});

// driver
Route::group(['prefix' => 'driver', 'middleware' => ['role:driver']], function () {
});
