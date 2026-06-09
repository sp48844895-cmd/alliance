<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GetInvolvedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LearningCornerController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ResourcesController;
use App\Http\Controllers\StoryController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ─── Home ───────────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.subscribe');

// ─── Static pages ───────────────────────────────────────────────────────────
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/campaigns', [CampaignsController::class, 'index'])->name('campaigns');
Route::get('/get-involved', [GetInvolvedController::class, 'index'])->name('get-involved');

// ─── Stories ────────────────────────────────────────────────────────────────
Route::get('/stories', [StoryController::class, 'index'])->name('stories');
Route::get('/stories/{slug}', [StoryController::class, 'show'])->name('stories.show');

// ─── Events ─────────────────────────────────────────────────────────────────
Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/events/calendar-data', [EventController::class, 'calendarData'])->name('events.calendar-data');
Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

// ─── Knowledge hub ──────────────────────────────────────────────────────────
Route::redirect('/knowledge-hub', '/learning-corner', 301)->name('knowledge-hub');
Route::get('/programs-and-initiatives', [ProgramController::class, 'index'])->name('programs');
Route::get('/resources', [ResourcesController::class, 'index'])->name('resources');
Route::get('/learning-corner', [LearningCornerController::class, 'index'])->name('learning-corner');
Route::get('/learning-corner/{main}/{sub}', [LearningCornerController::class, 'sub'])->name('learning-corner.sub')->whereNumber(['main', 'sub']);
Route::get('/learning-corner/{main}', [LearningCornerController::class, 'main'])->name('learning-corner.main')->whereNumber('main');
Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
Route::get('/reports/preview/{report}', [ReportsController::class, 'preview'])->name('reports.preview')->whereNumber('report');
Route::get('/magazine/{slug}', [ReportsController::class, 'magazine'])->name('magazine')->where('slug', '[a-z0-9\-]+');

// ─── Members ────────────────────────────────────────────────────────────────
Route::get('/members', [MembersController::class, 'index'])->name('members');

// ─── Contact ────────────────────────────────────────────────────────────────
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');

// ─── Registrations (get involved) ───────────────────────────────────────────
Route::redirect('/get-involved/register/volunteer', '/get-involved/register/guest', 301);
Route::get('/get-involved/register/guest', [RegistrationController::class, 'guestForm'])->name('register.guest');
Route::post('/get-involved/register/guest', [RegistrationController::class, 'guestStore'])->name('register.guest.submit')->middleware('throttle:10,1');
Route::get('/get-involved/register/intern', [RegistrationController::class, 'internForm'])->name('register.intern');
Route::post('/get-involved/register/intern', [RegistrationController::class, 'internStore'])->name('register.intern.submit')->middleware('throttle:10,1');
Route::get('/get-involved/register/fellowship', [RegistrationController::class, 'fellowForm'])->name('register.fellow');
Route::post('/get-involved/register/fellowship', [RegistrationController::class, 'fellowStore'])->name('register.fellow.submit')->middleware('throttle:10,1');

// ─── Authentication ───────────────────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::redirect('/login/volunteer', '/login/guest', 301);

Route::prefix('login')->whereIn('type', ['guest', 'intern', 'fellow', 'ngo', 'admin'])->group(function () {
    Route::get('/{type}', [LoginController::class, 'showForm'])->name('login.show');
    Route::post('/{type}', [LoginController::class, 'attempt'])->name('login.attempt')->middleware('throttle:5,1');
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');
