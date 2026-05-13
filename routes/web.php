<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/dashboard', '/');
Route::redirect('/admin', '/');

Route::middleware('auth')->group(function () {
    Route::get('/secure-file/{submission}/{type}', [\App\Http\Controllers\DownloadController::class, 'download'])->name('secure.file');
});
