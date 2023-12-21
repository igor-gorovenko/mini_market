<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminFileController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\FileSynchronizationController;
use App\Http\Controllers\StripeSettingController;

Auth::routes();

// Only admin
Route::middleware(['admin'])->prefix('/admin')->group(function () {
    // Files
    Route::prefix('/files')->group(function () {
        Route::get('/', [AdminFileController::class, 'files'])->name('admin.files.list');
        Route::post('/synchronize-files', [FileSynchronizationController::class, 'synchronizeFiles'])->name('admin.files.synchronize');
        Route::get('/sync-success', [FileSynchronizationController::class, 'syncSuccess'])->name('admin.files.sync.success');
        Route::get('/create', [AdminFileController::class, 'create'])->name('admin.files.create');
        Route::post('/store', [AdminFileController::class, 'store'])->name('admin.files.store');
        Route::get('/{slug}/edit', [AdminFileController::class, 'edit'])->name('admin.files.edit')->where('slug', '[a-zA-Z0-9_-]+');
        Route::put('/{slug}/update', [AdminFileController::class, 'update'])->name('admin.files.update')->where('slug', '[a-zA-Z0-9_-]+');
        Route::get('/{slug}/delete', [AdminFileController::class, 'destroy'])->name('admin.files.destroy')->where('slug', '[a-zA-Z0-9_-]+');
    });

    // Users
    Route::prefix('/users')->group(function () {
        Route::get('/', [AdminUserController::class, 'users'])->name('admin.users.list');
        Route::get('/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/store', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/{slug}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit')->where('slug', '[a-zA-Z0-9_-]+');
        Route::put('/{slug}/update', [AdminUserController::class, 'update'])->name('admin.users.update')->where('slug', '[a-zA-Z0-9_-]+');
        Route::get('/{slug}/delete', [AdminUserController::class, 'destroy'])->name('admin.users.destroy')->where('slug', '[a-zA-Z0-9_-]+');
    });

    // Settings
    Route::prefix('/settings')->group(function () {
        Route::get('/', [StripeSettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/update', [StripeSettingController::class, 'update'])->name('admin.settings.update');
    });
});


Route::get('/payment/session-cancel', [PaymentController::class, 'cancelSession'])->name('payment.cancel');
Route::post('/payment/session-create/{slug}', [PaymentController::class, 'createSession'])->name('payment.create')->where('slug', '[a-zA-Z0-9_-]+');
Route::get('/payment/session-success/{slug}', [PaymentController::class, 'successSession'])->name('payment.success')->where('slug', '[a-zA-Z0-9_-]+');

Route::get('/', [HomeController::class, 'index'])->name('site.index');
Route::get('/{slug}', [HomeController::class, 'show'])->name('site.show')->where('slug', '[a-zA-Z0-9_-]+');
