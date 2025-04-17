<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function (){
    Route::get('/account-dashboard',[\App\Http\Controllers\UserController::class,'index'])->name('user.dashboard');
});

Route::middleware(['auth',\App\Http\Middleware\AuthAdmin::class])->group(function (){
    Route::get('/account-dashboard',[\App\Http\Controllers\AdminController::class,'index'])->name('admin.dashboard');
});
