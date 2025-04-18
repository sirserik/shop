<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('main.page');

Route::middleware(['auth'])->group(function (){
    Route::get('/account-dashboard',[\App\Http\Controllers\UserController::class,'index'])->name('user.dashboard');
});

Route::middleware(['auth',\App\Http\Middleware\AuthAdmin::class])->group(function (){
    Route::get('/admin-dashboard',[\App\Http\Controllers\AdminController::class,'index'])->name('admin.dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/order-tracking', [OrderController::class, 'tracking'])->name('orders.tracking');

    Route::get('/slider', [SliderController::class, 'index'])->name('slider.index');
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');

});
