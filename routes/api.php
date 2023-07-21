<?php

use Illuminate\Http\Request;

// controller
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\LogVisitorController;
use App\Http\Controllers\Seller\TokoController;
use App\Http\Controllers\Seller\ProdukController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ProductAdvertisingController;

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

Route::post('/midtrans/callback', [OrderController::class, 'callback']);

Route::group(['prefix' => 'auth'], function () {
    Route::post('/user/register', [AuthenticationController::class, 'registerAsUser']);
    Route::post('/seller/register', [AuthenticationController::class, 'registerAsSeller']);
    Route::post('/driver/register', [AuthenticationController::class, 'registerAsDriver']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
});

// user
Route::group(['middleware' => ['role:user', 'auth:sanctum'], 'prefix' => 'user'], function () {

    // produk
    Route::get('/produk/all', [ProdukController::class, 'index']);

    // home
    Route::get('/produk/home/search/{keyword}', [ProdukController::class, 'home_search']);
    Route::get('/produk/promo/kilat', [SaleController::class, 'index']);
    Route::get('/produk/populer', [LogVisitorController::class, 'getProductPopuler']);
    Route::get('/produk/sering/user/kunjungi', [LogVisitorController::class, 'getUserMostVisitor']);

    // explore
    Route::get('/display-iklan', [ProductAdvertisingController::class, 'display_iklan']);
    Route::get('/produk/kategori', [ProdukController::class, 'nearestProdukByCategoryId']);
    Route::get('/kategori', [ProdukController::class, 'categories']);

    // profile
    Route::get('/profile', [UserController::class, 'index']);

    Route::group(['prefix' => '/toko'], function () {
        Route::get('/all', [TokoController::class, 'index']);
        Route::get('/detail', [TokoController::class, 'detail_toko']);

        Route::group(['prefix' => '/katalog'], function () {
            Route::get('/produkByCategoryId', [ProdukController::class, 'produkStoreByCategoryId']);
        });

        Route::get('/terderkat', [TokoController::class, 'getNearestStore']);
        Route::get('/popular', [LogVisitorController::class, 'mostPopularStore']);
    });

    Route::get('/produk/detail', [ProdukController::class, 'detail_produk']);

    // cart
    Route::group(['prefix' => '/produk/cart'], function () {
        Route::get('/', [CartController::class, 'listCart']);
        Route::get('/add', [CartController::class, 'addToChart']);
        Route::get('/minus', [CartController::class, 'minus']);
        Route::get('/delete', [CartController::class, 'deleteAll']);
        Route::get('/custom', [CartController::class, 'custom']);
        Route::get('/checkout', [CartController::class, 'checkout']);
        Route::post('/pesan', [OrderController::class, 'store']);
        Route::get('/updateStatus', [CartController::class, 'updateStatus']);
    });

    // order
    Route::group(['prefix' => '/status'], function () {
        Route::get('/pesanan', [OrderController::class, 'pesananUser']);
        Route::get('/disiapkan', [OrderController::class, 'disiapkanSeller']);
        Route::get('/diantar', [OrderController::class, 'sedangDiantar']);
        Route::get('/selesai', [OrderController::class, 'barangSampai']);
    });

    //ulasan 
    Route::post('/ulasan', [ReviewController::class, 'review']);
});

// seller
Route::group(['middleware' => ['role:seller', 'auth:sanctum'], 'prefix' => 'seller'], function () {
    Route::get('/profile', [TokoController::class, 'sellerPersonalInformation']);

    Route::get('/analysis', [TokoController::class, 'analysis']);
    Route::get('/pemasukan', [TokoController::class, 'income']);
    Route::get('/grafik/penjualan', [TokoController::class, 'graphic']);

    Route::group(['prefix' => '/produk'], function () {
        Route::post('/create', [ProdukController::class, 'create']);
        Route::get('/display', [ProdukController::class, 'listProduct']);
        Route::get('/display/verify', [ProdukController::class, 'onVerify']);
    });

    Route::group(['prefix' => '/status'], function () {
        Route::group(['prefix' => '/product'], function () {
            Route::get('/pesanan', [OrderController::class, 'pesanan']);
            Route::put('/update/konfirmasi', [OrderController::class, 'updateStatusPrepared']);
            Route::get('/disiapkan', [OrderController::class, 'disiapkan']);
            Route::put('/update/siap-diantar', [OrderController::class, 'updateStatusReadyToPicked']);
            Route::get('/menunggu-driver', [OrderController::class, 'menunggu_driver']);
            Route::get('/diantar', [OrderController::class, 'diantar']);
            Route::get('/selesai', [OrderController::class, 'selesai']);
        });
    });

    Route::group(['prefix' => '/iklan'], function () {
        Route::get('/kategori-toko', [ProductAdvertisingController::class, 'kategori']);
        Route::post('/add', [ProductAdvertisingController::class, 'add']);
    });
});

// admin
Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function () {
});

// driver
Route::group(['prefix' => 'driver', 'middleware' => ['role:driver']], function () {
});
