<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProgramRegistrationController;
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
Route::get('/knowledge-hub', fn () => redirect()->route('learning-corner', [], 301))->name('knowledge-hub');
Route::get('/programs-and-initiatives', [PageController::class, 'programsAndInitiatives'])->name('programs');
Route::get('/get-involved', [PageController::class, 'getInvolved'])->name('get-involved');
Route::get('/get-involved/register/volunteer', [ProgramRegistrationController::class, 'volunteerForm'])->name('register.volunteer');
Route::post('/get-involved/register/volunteer', [ProgramRegistrationController::class, 'volunteerStore'])->name('register.volunteer.submit')->middleware('throttle:10,1');
Route::get('/get-involved/register/intern', [ProgramRegistrationController::class, 'internForm'])->name('register.intern');
Route::post('/get-involved/register/intern', [ProgramRegistrationController::class, 'internStore'])->name('register.intern.submit')->middleware('throttle:10,1');
Route::get('/get-involved/register/fellowship', [ProgramRegistrationController::class, 'fellowForm'])->name('register.fellow');
Route::post('/get-involved/register/fellowship', [ProgramRegistrationController::class, 'fellowStore'])->name('register.fellow.submit')->middleware('throttle:10,1');
Route::get('/members', [PageController::class, 'members'])->name('members');
Route::get('/resources', [PageController::class, 'resources'])->name('resources');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');
Route::get('/learning-corner', [PageController::class, 'learningCorner'])->name('learning-corner');
Route::get('/learning-corner/cat/{id}', [PageController::class, 'learningCornerCategoryAjax'])->name('learning-corner.cat')->whereNumber('id');
Route::get('/reports', [PageController::class, 'reports'])->name('reports');
Route::get('/magazine/{slug}', [PageController::class, 'magazine'])->name('magazine')->where('slug', '[a-z0-9\-]+');
Route::post('/newsletter', [PageController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::prefix('login')->whereIn('type', ['volunteer', 'intern', 'fellow', 'ngo', 'admin'])->group(function () {
    Route::get('/{type}', [LoginController::class, 'showForm'])->name('login.show');
    Route::post('/{type}', [LoginController::class, 'attempt'])->name('login.attempt')->middleware('throttle:5,1');
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');
