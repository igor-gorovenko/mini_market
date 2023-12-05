<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;


Auth::routes();

Route::middleware(['admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});

Route::middleware(['user'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.index');
    Route::get('/{name}', [UserController::class, 'show'])->name('user.show')->where('name', '[a-zA-Z0-9_-]+');
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
