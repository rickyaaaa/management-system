<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware('auth')->group(function () {
    Route::get('/secure-file/{submission}/{type}', [\App\Http\Controllers\DownloadController::class, 'download'])->name('secure.file');
});

require __DIR__.'/auth.php';
