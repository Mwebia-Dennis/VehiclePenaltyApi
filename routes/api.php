<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\SearchUserController;
use App\Http\Controllers\User\UserVehicleController;
use App\Http\Controllers\User\UserMenuController;
use App\Http\Controllers\User\UserMenuItemController;
use App\Http\Controllers\User\UserPenaltyController;
use App\Http\Controllers\User\UserMenuDataController;
use App\Http\Controllers\MenuItem\MenuItemController;
use App\Http\Controllers\Menu\MenuController;
use App\Http\Controllers\MenuData\SearchMenuDataController;
use App\Http\Controllers\Menu\MenuItemController as MenuEntryController;
use App\Http\Controllers\Menu\MenuDataController as Menu_MenuDataController;
use App\Http\Controllers\Vehicle\VehicleController;
use App\Http\Controllers\Vehicle\VehiclePenaltyController;
use App\Http\Controllers\Vehicle\SearchVehicleController;
use App\Http\Controllers\Penalty\PenaltyController;
use App\Http\Controllers\Penalty\SearchPenaltyController;
use App\Http\Controllers\SystemStatistics\SystemStatiticsController;







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
    

    //authenticated user info
Route::get('auth/user-details', [AuthController::class, 'userDetails']);
    
    Route::post('auth/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    //users info
    
    Route::resource('users', UserController::class)->only(['show', 'index', 'update']);
    Route::post('users-search', [SearchUserController::class, 'index']);
    Route::resource('users.vehicle', UserVehicleController::class)->except(['edit', 'create', 'show']);
    Route::resource('users.penalty', UserPenaltyController::class)->except(['edit', 'create', 'show']);
    Route::resource('users.menu', UserMenuController::class)->only(['store']);
    Route::resource('users.menu-item', UserMenuItemController::class)->only(['store', 'index']);
    Route::resource('users.menu-data', UserMenuDataController::class)->only(['store', 'update', 'destroy']);
    Route::resource('menu-item', MenuItemController::class)->only(['index']);
    Route::resource('menu', MenuController::class)->only(['index', 'show']);
    Route::resource('menu.menu-item', MenuEntryController::class)->only(['index']);
    Route::resource('menu.menu-data', Menu_MenuDataController::class)->only(['index']);
    Route::resource('vehicle', VehicleController::class)->only(['index']);
    Route::post('vehicles-search', [SearchVehicleController::class, 'index']);
    Route::resource('penalty', PenaltyController::class)->only(['index']);
    Route::post('penalty-search', [SearchPenaltyController::class, 'index']);
    Route::resource('vehicle.penalty', VehiclePenaltyController::class)->only(['index']);
    Route::resource('statistics', SystemStatiticsController::class)->only(['index']);
    Route::resource('menu-data-search', SearchMenuDataController::class)->only(['index']);
    

});


Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@company.com'], 404);
});
