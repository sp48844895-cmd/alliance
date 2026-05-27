<?php

use App\Http\Controllers\Intern\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'intern'])->prefix('intern')->name('intern.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/work-log', [DashboardController::class, 'storeWorkLog'])->name('work-log.store');
});
