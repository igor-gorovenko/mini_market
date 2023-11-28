<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', [ProductController::class, 'index']);
Route::get('/{id}', [ProductController::class, 'show'])->where('id', '[0-9]+');
