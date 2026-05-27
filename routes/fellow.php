<?php

use App\Http\Controllers\Fellow\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'fellow'])->prefix('fellow')->name('fellow.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});
