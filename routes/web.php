<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;


Auth::routes();

Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('index');
});

Route::middleware(['user'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/{name}', [UserController::class, 'show'])->name('show')->where('name', '[a-zA-Z0-9_-]+');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
