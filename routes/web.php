<?php

use App\Http\Controllers\Web\User\HomeController;
use App\Http\Controllers\Web\User\ShopController;
use App\Http\Controllers\Web\User\CartController;
use App\Http\Controllers\Web\User\CheckoutController;
use App\Http\Controllers\Web\User\ProfileController;
use App\Http\Controllers\Web\User\WishlistController;
use App\Http\Controllers\Web\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Web\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Web\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Web\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\Web\Admin\UserController as AdminUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Routes (Website)
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Shop Routes
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('index');
    Route::get('/search', [ShopController::class, 'search'])->name('search');
    Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category');
    Route::get('/{slug}', [ShopController::class, 'show'])->name('show');
});

// Auth Routes (User) - using Laravel Fortify
Route::middleware(['auth', 'verified'])->group(function () {
    // Cart Routes
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::put('/{id}', [CartController::class, 'update'])->name('update');
        Route::put('/{id}/size', [CartController::class, 'updateSize'])->name('update-size');
        Route::delete('/{id}', [CartController::class, 'remove'])->name('remove');
        Route::post('/{id}/toggle', [CartController::class, 'toggleSelection'])->name('toggle');
        Route::post('/select-all', [CartController::class, 'selectAll'])->name('select-all');
    });

    // Wishlist Routes
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::post('/toggle/{productId}', [WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/{productId}', [WishlistController::class, 'remove'])->name('remove');
        Route::get('/check/{productId}', [WishlistController::class, 'check'])->name('check');
    });

    // Checkout Routes
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/', [CheckoutController::class, 'store'])->name('store');
        Route::get('/success/{orderNumber}', [CheckoutController::class, 'success'])->name('success');
    });

    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::get('/orders', [ProfileController::class, 'orders'])->name('orders');
        Route::get('/orders/{orderNumber}', [ProfileController::class, 'orderDetail'])->name('orders.show');
        Route::get('/wishlist', [ProfileController::class, 'wishlist'])->name('wishlist');
        Route::get('/addresses', [ProfileController::class, 'addresses'])->name('addresses');
        Route::post('/addresses', [ProfileController::class, 'storeAddress'])->name('addresses.store');
        Route::put('/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{address}', [ProfileController::class, 'deleteAddress'])->name('addresses.destroy');
        Route::post('/addresses/{address}/primary', [ProfileController::class, 'setPrimaryAddress'])->name('addresses.primary');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Admin Routes
    Route::middleware('guest.admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated Admin Routes
    Route::middleware('admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::resource('products', AdminProductController::class);
        Route::delete('/products/image/{image}', [AdminProductController::class, 'deleteImage'])->name('products.image.delete');
        Route::post('/products/image/{image}/primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.image.primary');

        // Categories
        Route::resource('categories', AdminCategoryController::class);

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
        Route::put('/orders/{order}/tracking', [AdminOrderController::class, 'updateTracking'])->name('orders.tracking');

        // Users Management
        Route::resource('users', AdminUserController::class);

        // Campaigns
        Route::resource('campaigns', AdminCampaignController::class);
        Route::post('/campaigns/{campaign}/banners', [AdminCampaignController::class, 'storeBanner'])->name('campaigns.banners.store');
        Route::delete('/campaigns/banners/{banner}', [AdminCampaignController::class, 'deleteBanner'])->name('campaigns.banners.delete');
    });
});
