<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('main.page');

// Маршруты для пользователей
Route::middleware(['auth'])->group(function (){
    Route::get('/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/account/address', [AccountController::class, 'address'])->name('account.address');
    Route::get('/account/details', [AccountController::class, 'details'])->name('account.details');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders.index');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
});

// Маршруты для администраторов
Route::middleware(['auth', \App\Http\Middleware\AuthAdmin::class])->prefix('admin')->group(function (){
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Продукты, бренды, категории и другие сущности
    Route::resource('products', ProductController::class)->names('admin.products');
    Route::resource('brands', BrandController::class)->names('admin.brands');
    Route::resource('categories', CategoryController::class)->names('admin.categories');
    Route::resource('orders', OrderController::class)->names('admin.orders');
    Route::get('/order-tracking', [OrderController::class, 'tracking'])->name('admin.orders.tracking');
    Route::resource('coupons', CouponController::class)->names('admin.coupons');
    Route::resource('slider', SliderController::class)->names('admin.slider');
    Route::resource('users', UserController::class)->names('admin.users');
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
});
