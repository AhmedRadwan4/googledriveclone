<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', [FileController::class, 'index']);
Route::resource('file', FileController::class);
Route::get('/starred', [FileController::class, 'starred'])->name('starred');
Route::get("search", [FileController::class, 'search']);