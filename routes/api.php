<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// controller
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\User\UserPersonalInformationController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Seller\TokoController;
use App\Http\Controllers\Seller\ProdukController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\LogVisitorController;

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

Route::group(['prefix'=>'auth'], function(){
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
    Route::post('/user/register', [AuthenticationController::class, 'registerAsUser']);
    Route::post('/seller/register', [AuthenticationController::class, 'registerAsSeller']);
    Route::post('/driver/register', [AuthenticationController::class, 'registerAsDriver']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
});

// user
Route::group(['middleware' => ['role:user', 'auth:sanctum'], 'prefix' => 'user'], function(){
    Route::get('/profile', [UserController::class, 'index']);
    Route::get('/toko', [TokoController::class, 'index']);
    Route::get('/produk', [KatalogController::class, 'index']);
    Route::get('/produk/detail', [ProdukController::class, 'detail']);
    Route::get('/produk/populer', [LogVisitorController::class, 'getProductPopuler']);
    Route::get('/produk/sering-dikunjungi', [LogVisitorController::class, 'getUserMostVisitor']);
});


// admin
Route::group(['prefix'=>'admin', 'middleware'=> ['role:admin']], function(){});
// seller
Route::group(['prefix'=>'seller', 'middleware'=> ['role:seller']], function(){});
// driver
Route::group(['prefix'=>'driver', 'middleware'=> ['role:driver']], function(){});