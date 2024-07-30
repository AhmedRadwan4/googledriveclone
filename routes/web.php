<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return redirect('file');
});
Route::resource('file', FileController::class);
Route::view('starred', 'file.starred')->name('starred');
Route::get("/recent", [FileController::class, 'recent'])->name('recent');
Route::get("/trash", [FileController::class, 'trash'])->name('trash');
Route::get("search", [FileController::class, 'search']);
