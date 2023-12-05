<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;


Auth::routes();

Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('index');
});

Route::middleware(['user'])->group(function () {
    Route::get('/', [FileController::class, 'index'])->name('index');
    Route::get('/{id}', [FileController::class, 'show'])->name('show')->where('id', '[0-9]+');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
