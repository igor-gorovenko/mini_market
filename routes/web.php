<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;


Auth::routes();

Route::middleware(['admin'])->prefix('/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    // Files
    Route::prefix('/files')->group(function () {
        Route::get('/', [AdminController::class, 'files'])->name('admin.files.list');
        Route::get('/{name}', [AdminController::class, 'show'])->name('admin.files.show')->where('name', '[a-zA-Z0-9_-]+');
        Route::get('/create', [AdminController::class, 'create'])->name('admin.files.create');
        Route::get('/{name}/edit', [AdminController::class, 'edit'])->name('admin.files.edit')->where('name', '[a-zA-Z0-9_-]+');
        Route::post('/{name}/edit', [AdminController::class, 'update'])->name('admin.files.update')->where('name', '[a-zA-Z0-9_-]+');

        Route::get('/{name}/delete', [AdminController::class, 'delete'])->name('admin.files.delete')->where('name', '[a-zA-Z0-9_-]+');
    });

    // Users
    Route::prefix('/users')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::get('/{name}', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::get('/{name}/delete', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    });

    //Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
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
