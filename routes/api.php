<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource("/stores", StoresController::class);
Route::get("/user", [UsersController::class, "get"]);
Route::post("/user", [UsersController::class, "post"]);
Route::post("/login", [LoginController::class, "post"]);
Route::post("/logout", [LogoutController::class, "post"]);
Route::get("/user/{user_id}/favorite", [UsersController::class, "favorites"]);
Route::post("/favorite", [FavoritesController::class, "post"]);
Route::delete("/favorite", [FavoritesController::class, "delete"]);
Route::get("/user/{user_id}/booking", [UsersController::class, "bookings"]);
Route::post("/booking", [BookingController::class, "post"]);
Route::delete("/booking", [BookingController::class, "delete"]);
