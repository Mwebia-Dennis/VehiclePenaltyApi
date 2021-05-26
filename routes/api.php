<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

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


// Route::middleware('auth:api')->group(function (Request $request) {
    




// });


Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@company.com'], 404);
});
