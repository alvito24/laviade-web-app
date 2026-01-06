<?php

use App\Http\Controllers\Api\V1\User\AuthController;
use App\Http\Controllers\Api\V1\User\ProductController;
use App\Http\Controllers\Api\V1\User\CartController;
use App\Http\Controllers\Api\V1\User\OrderController;
use App\Http\Controllers\Api\V1\User\ReviewController;
use App\Http\Controllers\Api\V1\User\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Public Routes
    |--------------------------------------------------------------------------
    */

    // Auth
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/new-arrivals', [ProductController::class, 'newArrivals']);
    Route::get('/products/best-sellers', [ProductController::class, 'bestSellers']);
    Route::get('/products/{slug}', [ProductController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Protected Routes (Require Auth)
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        // Cart
        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart', [CartController::class, 'store']);
        Route::put('/cart/{id}', [CartController::class, 'update']);
        Route::delete('/cart/{id}', [CartController::class, 'destroy']);
        Route::post('/cart/{id}/toggle', [CartController::class, 'toggleSelection']);

        // Orders & Checkout
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{orderNumber}', [OrderController::class, 'show']);
        Route::post('/checkout', [OrderController::class, 'checkout']);
        Route::post('/orders/{orderNumber}/cancel', [OrderController::class, 'cancel']);

        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store']);

        // Profile
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::put('/profile', [ProfileController::class, 'update']);

        // Addresses
        Route::get('/addresses', [ProfileController::class, 'addresses']);
        Route::post('/addresses', [ProfileController::class, 'storeAddress']);
        Route::put('/addresses/{address}', [ProfileController::class, 'updateAddress']);
        Route::delete('/addresses/{address}', [ProfileController::class, 'deleteAddress']);
    });
});
