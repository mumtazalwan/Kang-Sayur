<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// controller
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\User\UserPersonalInformationController;
use App\Http\Controllers\User\UserController;

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
    Route::post('/user/register', [AuthenticationController::class, 'registerAsUser']);
    Route::post('/seller/register', [AuthenticationController::class, 'registerAsSeller']);
    Route::post('/driver/register', [AuthenticationController::class, 'registerAsDriver']);

    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
    Route::get('/profile', [AuthenticationController::class, 'getPersonalInformation'])->middleware(['auth:sanctum']);
    // Route::get('/sandi', [AuthenticationController::class, 'getSandi'])->middleware(['auth:sanctum']);
});

// user
Route::group(['middleware' => ['role:user', 'auth:sanctum'], 'prefix' => 'user'], function(){
    Route::get('/profile', [UserController::class, 'index']);
    Route::get('/logout', [UserPersonalInformationController::class, 'logout']);
});
// admin
Route::group(['prefix'=>'user', 'middleware'=> ['role:admin']], function(){});
// seller
Route::group(['prefix'=>'seller', 'middleware'=> ['role:seller']], function(){});
// driver
Route::group(['prefix'=>'driver', 'middleware'=> ['role:driver']], function(){});