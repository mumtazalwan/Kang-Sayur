<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// controller
use App\Http\Controllers\AuthenticationController;

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

Route::group(['prefix'=>'auth', 'auth:sanctum'], function(){
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
    Route::get('/profile', [AuthenticationController::class, 'getPersonalInformation'])->middleware(['auth:sanctum']);
});
