<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;


Auth::routes();

Route::middleware(['admin'])->prefix('/admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    // Files
    Route::prefix('/files')->group(function () {
        Route::get('/', [AdminController::class, 'files'])->name('admin.files.list');
        Route::get('/create', [AdminController::class, 'create'])->name('admin.files.create');
        Route::post('/store', [AdminController::class, 'store']);

        Route::get('/{name}', [AdminController::class, 'show'])->name('admin.files.show')->where('name', '[a-zA-Z0-9_-]+');
        Route::get('/{name}/edit', [AdminController::class, 'edit'])->name('admin.files.edit')->where('name', '[a-zA-Z0-9_-]+');
        Route::post('/{name}/edit', [AdminController::class, 'update'])->where('name', '[a-zA-Z0-9_-]+');

        Route::get('/{name}/delete', [AdminController::class, 'delete'])->where('name', '[a-zA-Z0-9_-]+');
    });

    // Users
    Route::prefix('/users')->group(function () {

        Route::get('/', [AdminController::class, 'users'])->name('admin.users.list');

        Route::get('/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::get('/store', [AdminController::class, 'storeUser']);

        Route::get('{name}', [AdminController::class, 'showUser'])
            ->name('admin.users.show')
            ->where('name', '[a-zA-Z0-9_ -]+'); // Добавлен пробел для имен

        Route::get('/{name}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit')->where('name', '[a-zA-Z0-9_-]+');
        Route::post('/{name}/edit', [AdminController::class, 'updateUser'])->where('name', '[a-zA-Z0-9_-]+');

        Route::get('/{name}/delete', [AdminController::class, 'deleteUser'])->where('name', '[a-zA-Z0-9_-]+');
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
