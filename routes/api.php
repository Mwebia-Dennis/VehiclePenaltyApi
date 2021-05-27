<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserVehicleController;
use App\Http\Controllers\User\UserMenuController;
use App\Http\Controllers\User\UserMenuItemController;
use App\Http\Controllers\MenuItem\MenuItemController;
use App\Http\Controllers\Menu\MenuController;
use App\Http\Controllers\Menu\MenuItemController as MenuEntryController;



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



Route::post('auth/signup', [AuthController::class, 'signup']);
Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('auth/check-account', [AuthController::class, 'checkEmail']);




Route::middleware('auth:api')->group(function () {
    



    Route::post('auth/update-profile', [AuthController::class, 'updateProfile']);
    //users info
    
    Route::resource('users', UserController::class)->only(['show', 'index', 'update']);
    Route::resource('users.vehicle', UserVehicleController::class)->except(['edit', 'create', 'show']);
    Route::resource('users.menu', UserMenuController::class)->only(['store']);
    Route::resource('users.menu-item', UserMenuItemController::class)->only(['store', 'index']);
    Route::resource('menu-item', MenuItemController::class)->only(['index']);
    Route::resource('menu', MenuController::class)->only(['index']);
    Route::resource('menu.menu-item', MenuEntryController::class)->only(['index']);

});


Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@company.com'], 404);
});
