<?php

use App\Http\Controllers\Preview\AboutController;
use App\Http\Controllers\Preview\CampaignsController;
use App\Http\Controllers\Preview\ContactController;
use App\Http\Controllers\Preview\EventController;
use App\Http\Controllers\Preview\GetInvolvedController;
use App\Http\Controllers\Preview\HomeController;
use App\Http\Controllers\Preview\LearningCornerController;
use App\Http\Controllers\Preview\MembersController;
use App\Http\Controllers\Preview\NewsletterController;
use App\Http\Controllers\Preview\ProgramController;
use App\Http\Controllers\Preview\RegistrationController;
use App\Http\Controllers\Preview\ReportsController;
use App\Http\Controllers\Preview\ResourcesController;
use App\Http\Controllers\Preview\StoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Preview routes — new restructure (does NOT replace the live public site)
| Visit: http://127.0.0.1:8000/preview/
|--------------------------------------------------------------------------
*/
Route::prefix('preview')->name('preview.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.subscribe');

    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::get('/campaigns', [CampaignsController::class, 'index'])->name('campaigns');
    Route::get('/get-involved', [GetInvolvedController::class, 'index'])->name('get-involved');

    Route::get('/stories', [StoryController::class, 'index'])->name('stories');
    Route::get('/stories/{slug}', [StoryController::class, 'show'])->name('stories.show');

    Route::get('/events', [EventController::class, 'index'])->name('events');
    Route::get('/events/calendar-data', [EventController::class, 'calendarData'])->name('events.calendar-data');
    Route::get('/events/{slug}', [EventController::class, 'show'])->name('events.show');

    Route::redirect('/knowledge-hub', '/preview/learning-corner', 301);
    Route::get('/programs-and-initiatives', [ProgramController::class, 'index'])->name('programs');
    Route::get('/resources', [ResourcesController::class, 'index'])->name('resources');
    Route::get('/learning-corner', [LearningCornerController::class, 'index'])->name('learning-corner');
    Route::get('/learning-corner/{main}/{sub}', [LearningCornerController::class, 'sub'])->name('learning-corner.sub')->whereNumber(['main', 'sub']);
    Route::get('/learning-corner/{main}', [LearningCornerController::class, 'main'])->name('learning-corner.main')->whereNumber('main');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/preview/{report}', [ReportsController::class, 'preview'])->name('reports.preview')->whereNumber('report');
    Route::get('/magazine/{slug}', [ReportsController::class, 'magazine'])->name('magazine')->where('slug', '[a-z0-9\-]+');

    Route::get('/members', [MembersController::class, 'index'])->name('members');

    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.submit');

    Route::redirect('/get-involved/register/volunteer', '/preview/get-involved/register/guest', 301);
    Route::get('/get-involved/register/guest', [RegistrationController::class, 'guestForm'])->name('register.guest');
    Route::post('/get-involved/register/guest', [RegistrationController::class, 'guestStore'])->name('register.guest.submit')->middleware('throttle:10,1');
    Route::get('/get-involved/register/intern', [RegistrationController::class, 'internForm'])->name('register.intern');
    Route::post('/get-involved/register/intern', [RegistrationController::class, 'internStore'])->name('register.intern.submit')->middleware('throttle:10,1');
    Route::get('/get-involved/register/fellowship', [RegistrationController::class, 'fellowForm'])->name('register.fellow');
    Route::post('/get-involved/register/fellowship', [RegistrationController::class, 'fellowStore'])->name('register.fellow.submit')->middleware('throttle:10,1');
});
