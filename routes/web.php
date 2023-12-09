<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminFileController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\UserController;

Auth::routes();

Route::get('/', function () {
    $user = Auth::user();
    if ($user) {
        if ($user->is_admin) {
            return redirect()->route('admin.index');
        } else {
            return redirect()->route('user.index');
        }
    }
    return view('auth.login');
})->name('index');

Route::middleware(['admin'])->prefix('/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    // Files
    Route::prefix('/files')->group(function () {
        Route::get('/', [AdminFileController::class, 'files'])->name('admin.files.list');
        Route::get('/create', [AdminFileController::class, 'create'])->name('admin.files.create');
        Route::post('/store', [AdminFileController::class, 'store'])->name('admin.files.store');
        Route::get('/{slug}', [AdminFileController::class, 'show'])->name('admin.files.show')->where('slug', '[a-zA-Z0-9_-]+');
        Route::get('/{slug}/edit', [AdminFileController::class, 'edit'])->name('admin.files.edit')->where('slug', '[a-zA-Z0-9_-]+');
        Route::put('/{slug}/update', [AdminFileController::class, 'update'])->name('admin.files.update')->where('slug', '[a-zA-Z0-9_-]+');
        Route::get('/{slug}/delete', [AdminFileController::class, 'destroy'])->name('admin.files.destroy')->where('slug', '[a-zA-Z0-9_-]+');
    });

    // Users
    Route::prefix('/users')->group(function () {
        Route::get('/', [AdminUserController::class, 'users'])->name('admin.users.list');
        Route::get('/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/store', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/{slug}', [AdminUserController::class, 'show'])->name('admin.users.show')->where('slug', '[a-zA-Z0-9_-]+');
        Route::get('/{slug}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit')->where('slug', '[a-zA-Z0-9_-]+');
        Route::put('/{slug}/update', [AdminUserController::class, 'update'])->name('admin.users.update')->where('slug', '[a-zA-Z0-9_-]+');
        Route::get('/{slug}/delete', [AdminUserController::class, 'destroy'])->name('admin.users.destroy')->where('slug', '[a-zA-Z0-9_-]+');
    });
});

Route::middleware(['user'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/{slug}', [UserController::class, 'show'])->name('user.show')->where('slug', '[a-zA-Z0-9_-]+');
});

Route::get('/', function () {
    $user = Auth::user();
    if ($user) {
        if ($user->is_admin) {
            return redirect()->route('admin.index');
        } else {
            return redirect()->route('user.index');
        }
    }
    return view('auth.login');
})->name('index');
