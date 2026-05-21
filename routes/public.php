<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/campaigns', [PageController::class, 'campaigns'])->name('campaigns');
Route::get('/events', [PageController::class, 'events'])->name('events');
Route::get('/events/calendar-data', [PageController::class, 'calendarData'])->name('events.calendar-data');
Route::get('/events/{slug}', [PageController::class, 'event'])->name('events.show');
Route::get('/stories', [PageController::class, 'stories'])->name('stories');
Route::get('/stories/{slug}', [PageController::class, 'story'])->name('stories.show');
Route::get('/knowledge-hub', [PageController::class, 'knowledgeHub'])->name('knowledge-hub');
Route::get('/get-involved', [PageController::class, 'getInvolved'])->name('get-involved');
Route::get('/members', [PageController::class, 'members'])->name('members');
Route::get('/resources', [PageController::class, 'resources'])->name('resources');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');
Route::get('/learning-corner', [PageController::class, 'learningCorner'])->name('learning-corner');
Route::get('/learning-corner/cat/{id}', [PageController::class, 'learningCornerCategoryAjax'])->name('learning-corner.cat')->whereNumber('id');
Route::get('/reports', [PageController::class, 'reports'])->name('reports');
Route::post('/newsletter', [PageController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::prefix('login')->whereIn('type', ['admin', 'author', 'volunteer', 'intern', 'professional', 'pro', 'ngo'])->group(function () {
    Route::get('/{type}', [LoginController::class, 'showForm'])->name('login.show');
    Route::post('/{type}', [LoginController::class, 'attempt'])->name('login.attempt')->middleware('throttle:5,1');
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');
