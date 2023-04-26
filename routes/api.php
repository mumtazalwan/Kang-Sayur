<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// controller
use App\Http\Controllers\AuthenticationController;
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

Route::group(['prefix'=>'auth', 'auth:sanctum'], function(){
    Route::post('/register', [AuthenticationController::class, 'register']);
    Route::post('/login', [AuthenticationController::class, 'login']);
    Route::get('/logout', [AuthenticationController::class, 'logout'])->middleware(['auth:sanctum']);
    Route::get('/profile', [AuthenticationController::class, 'getPersonalInformation'])->middleware(['auth:sanctum']);
});

Route::get('give-permission-to-role', function(){
    $role = Role::findOrFail(1);

    $permission = Permission::findOrFail(1);
    // $permission2 = Permission::findOrFail(2);
    // $permission3 = Permission::findOrFail(3);

    $role->givePermissionTo([$permission]);
});

Route::get('assign-role-to-user', function(){
    $user = User::findOrFail(2);

    $role = Role::findOrFail(1);
    // $role2 = Role::findOrFail(2);
    // $role3 = Role::findOrFail(3);

    $user->assignRole([$role]);
});

// Route::get('spatie-method', function(){
//     $user = User::findOrFail(4);
//     dd($user->getPermissionsViaRoles());
// });

$user = User::findOrFail(2);
Auth::logout();

Route::get('create-product', function(){
    dd('this is create feature that only can be accessed by user and seller');
})->middleware(['can:create product']);

Route::get('edit-product', function(){
    dd('this is edit feature that only can be accessed by user and seller');
})->middleware(['can:edit product']);

// Route::get('delete-product', function(){
//     dd('this is delete feature that only can be accessed by admin');
// })->middleware(['can:delete product']);