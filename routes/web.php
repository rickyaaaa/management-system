<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware('auth')->group(function () {
    Route::get('/secure-file/{submission}/{type}', [\App\Http\Controllers\DownloadController::class, 'download'])->name('secure.file');
});

Route::view('staff-directory', 'staff-directory')
    ->middleware(['auth', 'verified', 'superadmin'])
    ->name('staff-directory');

Route::view('manage-tasks', 'manage-tasks')
    ->middleware(['auth', 'verified', 'superadmin'])
    ->name('manage-tasks');

require __DIR__.'/auth.php';
