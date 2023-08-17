<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Delivery;
use App\Http\Controllers\Driver\DriverController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\OtpController;
use Illuminate\Http\Request;

// controller
use Illuminate\Support\Facades\Mail;
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
use App\Mail\OtpMail;

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

Route::group(['prefix' => '/otp'], function () {
    Route::post('/generate', [OtpController::class, 'generate']);
    Route::post('/verify', [OtpController::class, 'verify']);
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/user/register', [AuthenticationController::class, 'registerAsUser']);
    Route::post('/seller/register', [AuthenticationController::class, 'registerAsSeller']);
    Route::post('/driver/register', [AuthenticationController::class, 'registerAsDriver']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
    Route::get('/update/device/token', [AuthenticationController::class, 'device_token_update']);
});

// user
Route::group(['middleware' => ['role:user', 'auth:sanctum'], 'prefix' => 'user'], function () {

    // produk
    Route::get('/produk/all', [ProdukController::class, 'index']);

    Route::group(['prefix' => '/password'], function () {
        Route::get('/send/mail', [AuthenticationController::class, 'sendEmailResetPassword']);
        Route::post('/update', [AuthenticationController::class, 'updatePassword']);
    });

    // home
    Route::get('/produk/home/search/{keyword}', [ProdukController::class, 'home_search']);
    Route::get('/produk/promo/kilat', [SaleController::class, 'index']);
    Route::get('/produk/populer', [LogVisitorController::class, 'getProductPopuler']);
    Route::get('/produk/sering/user/kunjungi', [LogVisitorController::class, 'getUserMostVisitor']);

    // explore
    Route::get('/display-iklan', [ProductAdvertisingController::class, 'display_iklan']);
    Route::get('/produk/kategori', [ProdukController::class, 'nearestProdukByCategoryId']);
    Route::get('/kategori', [ProdukController::class, 'categories']);
    Route::get('/produk/terlaris', [ProdukController::class, 'terlaris']);

    // profile
    Route::get('/profile', [UserController::class, 'index']);
    Route::post('/update-profile', [UserController::class, 'updateUser']);

    // address
    Route::group(['prefix' => '/alamat'], function () {
        Route::get('/list', [UserController::class, 'list_alamat']);
        Route::post('/tambah', [UserController::class, 'tambah_alamat']);
        Route::post('/update', [UserController::class, 'updateAlamat']);
    });

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
        Route::post('/instantbuy', [CartController::class, 'instantbuy']);
        Route::post('/pesan', [OrderController::class, 'store']);
        Route::get('/updateStatus', [CartController::class, 'updateStatus']);
        Route::get('/total/selected', [CartController::class, 'selected']);
    });

    // order
    Route::group(['prefix' => '/status'], function () {
        Route::get('/pesanan', [OrderController::class, 'pesananUser']);
        Route::get('/disiapkan', [OrderController::class, 'disiapkanSeller']);
        Route::get('/diantar', [OrderController::class, 'sedangDiantar']);
        Route::get('/selesai', [OrderController::class, 'barangSampai']);
    });

    //ulasan
    Route::get('/menunggu-diulas', [ReviewController::class, 'menunggu_diulas']);
    Route::post('/ulasan', [ReviewController::class, 'review']);
    Route::get('/riwayat-ulasan', [ReviewController::class, 'riwayat']);
});

// seller
Route::group(['middleware' => ['role:seller', 'auth:sanctum'], 'prefix' => 'seller'], function () {

    Route::group(['prefix' => '/password'], function () {
        Route::get('/send/mail', [AuthenticationController::class, 'sendEmailResetPassword']);
        Route::post('/update', [AuthenticationController::class, 'updatePassword']);
    });

    Route::get('/profile', [TokoController::class, 'sellerPersonalInformation']);
    Route::post('/edit/profile', [TokoController::class, 'editToko']);

    Route::get('/analysis', [TokoController::class, 'analysis']);
    Route::get('/pemasukan', [TokoController::class, 'income']);
    Route::get('/grafik/penjualan', [TokoController::class, 'graphic']);

    Route::group(['prefix' => '/sale'], function () {
        Route::get('/session', [SaleController::class, 'session']);
        Route::post('/create', [SaleController::class, 'create']);
    });

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


    Route::group(['prefix' => '/driver'], function () {
        Route::get('/list', [TokoController::class, 'list_driver']);
        Route::post('/delete', [DriverController::class, 'deleteDriver']);
        Route::post('/update/password', [DriverController::class, 'updatePassword']);
    });

    Route::post('/register/driver', [AuthenticationController::class, 'registerAsDriver']);

    Route::group(['prefix' => '/review'], function () {
        Route::get('/belum-dibalas', [ReviewController::class, 'csreview']);
        Route::get('/all', [ReviewController::class, 'allreview']);
        Route::put('/reply', [ReviewController::class, 'reply']);
    });

    Route::group(['prefix' => '/inbox'], function () {
        Route::get('/data-inbox', [InboxController::class, 'listInbox']);
    });
});

// admin
Route::group(['middleware' => ['role:admin', 'auth:sanctum'], 'prefix' => 'admin'], function () {
    Route::get('/profile', [UserController::class, 'adminProfile']);

    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    Route::group(['prefix' => '/toko'], function () {
        Route::get('/search/{keyword}', [TokoController::class, 'search_toko']);
        Route::get('/list', [TokoController::class, 'all']);
        Route::get('/detail', [TokoController::class, 'detail_toko_admin']);
        Route::get('/produk', [TokoController::class, 'list_produk_toko']);
    });

    Route::group(['prefix' => '/produk'], function () {
        Route::post('/verifikasi', [ProdukController::class, 'verifikasi']);
        Route::get('/verifikasi/list', [ProdukController::class, 'verifikasi_list']);
    });
});

// driver
Route::group(['middleware' => ['role:driver', 'auth:sanctum'], 'prefix' => 'driver'], function () {

    Route::get('/profile', [DriverController::class, 'driverInfo']);
    Route::get('/analisa', [DriverController::class, 'analisa']);

    Route::group(['prefix' => '/order'], function () {
        Route::get('/list', [OrderController::class, 'readyToPickedUp']);
        Route::put('/antar', [Delivery::class, 'takeOrder']);
        Route::get('/updateLoc', [Delivery::class, 'updateLoc']);
        Route::put('/update/status', [Delivery::class, 'finishOrder']);
    });

    Route::group(['prefix' => '/riwayat'], function () {
        Route::get('/selesai/diantar', [Delivery::class, 'delivered']);
    });
});
