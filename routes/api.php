<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\Food\CartController;
use App\Http\Controllers\Api\Food\HomeController as FoodHomeController;
use App\Http\Controllers\Api\Food\OrderController as FoodOrderController;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Ecom\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StrategyController;

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

// global authentic
Route::get('/', [HomeController::class, 'index']);

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/send-otp', [RegisterController::class, 'sendOtp']);
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp']);








// authentic routes
Route::middleware(['auth:sanctum'])->group(function () {

    // admin middleware
    Route::middleware(['checkAdmin:admin'])->group(function () {
        Route::post('/wallet-top-up',[UserController::class, 'wallet_top_up_store']);
        
    });
    
    // user middleware
    Route::middleware(['checkAdmin:user'])->group(function () {
    });

    
    // admin middleware
    Route::middleware(['checkAdmin:'])->group(function () {
    });

    Route::get('/profile', [RegisterController::class, 'profile']);
    Route::post('/profile', [RegisterController::class, 'updateProfile']);

    //transaction route
    Route::post('/wallet-top-up',[UserController::class, 'wallet_top_up_store']);
    Route::get('/transaction/details/{id}',[UserController::class, 'show']);
    Route::get('/transaction/listing',[UserController::class, 'listing']);

    //get the 3 table
    Route::get('/interest-dynamic-form',[UserController::class, 'interest_dynamic_form']);
    Route::post('/store-interest-form',[UserController::class, 'store_interest_form']);

    //strategy information
    Route::get('/strategy/details/{id}',[StrategyController::class, 'show']);
    Route::get('/strategy/listing',[StrategyController::class, 'listing']);


    //address route
    Route::get('/address', [AddressController::class, 'index']);
    Route::post('/add/address', [AddressController::class, 'addAddress']);
    Route::get('/address/delete/{id?}', [AddressController::class, 'destroy']);

    Route::group(['prefix' => 'foods', 'namespace' => 'Api/Food'], function () {
        Route::get('/', [FoodHomeController::class, 'index']);
        Route::get('/details/{id}', [FoodHomeController::class, 'show']);
        Route::get('/search/{search?}', [FoodHomeController::class, 'search']);
        Route::post('/add-to-cart', [CartController::class, 'addToCart']);
        Route::get('/cart', [CartController::class, 'cartList']);
        Route::get('/remove-item-cart/{id}', [CartController::class, 'removeItemCart']);
        Route::post('/place-order', [FoodOrderController::class, 'place_order']);
    });

    Route::group(['prefix' => 'ecom', 'namespace' => 'Api/Ecom'], function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/listing/{id?}', [ProductController::class, 'listing']);
        Route::get('/cart/listing', [ProductController::class, 'cartListing']);
    });







    Route::get('/logout', [RegisterController::class, 'logout']);
});

Route::post('/tokens/create', [HomeController::class, 'tokenCreate']);
