<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ManagersController;
use App\Http\Controllers\StoreManagersController;

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

Route::get("/stores/{user_id}", [StoresController::class,"get"]);
Route::get("/store/{id}", [StoresController::class,"getStore"]);
Route::get("/storesSeach/{user_id}",[StoresController::class,"seachStore"]);
Route::post("/stores", [StoresController::class, "post"]);
Route::post("/storeImageUpdate", [StoresController::class, "store_image_update"]);
Route::put("/stores", [StoresController::class, "put"]);
Route::delete("/stores", [StoresController::class, "delete"]);
Route::get("/user", [UsersController::class, "get"]);
Route::post("/user", [UsersController::class, "post"]);
Route::post("/login", [LoginController::class, "post"]);
Route::post("/logout", [LogoutController::class, "post"]);
Route::get("/user/{user_id}/favorite", [FavoritesController::class, "get"]);
Route::post("/favorite", [FavoritesController::class, "favorites"]);
Route::get("/user/{user_id}/booking", [BookingController::class, "get"]);
Route::post("/booking", [BookingController::class, "post"]);
Route::put("/booking", [BookingController::class, "put"]);
Route::delete("/booking", [BookingController::class, "delete"]);
Route::post("/manage/create", [ManagersController::class, "post"]);
Route::post("/manage/login", [ManagersController::class, "login"]);
Route::post("/manage/storeManager/create", [StoreManagersController::class, "post"]);
Route::post("/manage/storeManager/login", [StoreManagersController::class, "login"]);