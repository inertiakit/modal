<?php

use App\Http\Controllers\MovieDetailModalController;
use App\Http\Controllers\MovieIndexController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', MovieIndexController::class)
    ->name('home');
Route::get('/movies/{movie}', MovieDetailModalController::class)
    ->name('movies');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
