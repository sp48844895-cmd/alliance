<?php

use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\LearningCategoryController;
use App\Http\Controllers\Admin\LearningCornerController;
use App\Http\Controllers\Admin\MailController;
use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\SbcPoolMemberController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\StoryController as AdminStoryController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Author\DashboardController as AuthorDashboardController;
use App\Http\Controllers\Author\ProfileController as AuthorProfileController;
use App\Http\Controllers\Author\StoryController as AuthorStoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'author'])->prefix('author')->name('author.')->group(function () {
    Route::get('/', [AuthorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AuthorProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AuthorProfileController::class, 'update'])->name('profile.update');
    Route::get('/stories', [AuthorStoryController::class, 'index'])->name('stories.index');
    Route::get('/stories/create', [AuthorStoryController::class, 'create'])->name('stories.create');
    Route::post('/stories', [AuthorStoryController::class, 'store'])->name('stories.store');
    Route::get('/stories/{id}/edit', [AuthorStoryController::class, 'edit'])->name('stories.edit');
    Route::put('/stories/{id}', [AuthorStoryController::class, 'update'])->name('stories.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/blogs', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
    Route::get('/blogs/{id}', [BlogController::class, 'show'])->name('blogs.show');
    Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
    Route::get('/blogs/{id}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::put('/blogs/{id}', [BlogController::class, 'update'])->name('blogs.update');
    Route::post('/blogs/{id}/toggle', [BlogController::class, 'toggleStatus'])->name('blogs.toggle');
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy'])->name('blogs.destroy');

    Route::get('/stories', [AdminStoryController::class, 'index'])->name('stories.index');
    Route::get('/stories/{id}', [AdminStoryController::class, 'show'])->name('stories.show');
    Route::post('/stories/{id}/approve', [AdminStoryController::class, 'approve'])->name('stories.approve');
    Route::post('/stories/{id}/reject', [AdminStoryController::class, 'reject'])->name('stories.reject');
    Route::delete('/stories/{id}', [AdminStoryController::class, 'destroy'])->name('stories.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/categories/{id}/toggle', [CategoryController::class, 'toggleStatus'])->name('categories.toggle');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::post('/events/{id}/toggle', [EventController::class, 'toggleStatus'])->name('events.toggle');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

    Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
    Route::get('/banners/create', [BannerController::class, 'create'])->name('banners.create');
    Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
    Route::get('/banners/{id}/edit', [BannerController::class, 'edit'])->name('banners.edit');
    Route::put('/banners/{id}', [BannerController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');

    Route::get('/sbc-pool', [SbcPoolMemberController::class, 'index'])->name('sbc-pool.index');
    Route::get('/sbc-pool/create', [SbcPoolMemberController::class, 'create'])->name('sbc-pool.create');
    Route::post('/sbc-pool', [SbcPoolMemberController::class, 'store'])->name('sbc-pool.store');
    Route::get('/sbc-pool/{id}/edit', [SbcPoolMemberController::class, 'edit'])->name('sbc-pool.edit');
    Route::put('/sbc-pool/{id}', [SbcPoolMemberController::class, 'update'])->name('sbc-pool.update');
    Route::post('/sbc-pool/{id}/toggle', [SbcPoolMemberController::class, 'toggleStatus'])->name('sbc-pool.toggle');
    Route::delete('/sbc-pool/{id}', [SbcPoolMemberController::class, 'destroy'])->name('sbc-pool.destroy');

    Route::get('/learning-cats', [LearningCategoryController::class, 'index'])->name('learning-cats.index');
    Route::post('/learning-cats', [LearningCategoryController::class, 'store'])->name('learning-cats.store');
    Route::get('/learning-cats/{id}/edit', [LearningCategoryController::class, 'edit'])->name('learning-cats.edit');
    Route::put('/learning-cats/{id}', [LearningCategoryController::class, 'update'])->name('learning-cats.update');
    Route::post('/learning-cats/{id}/toggle', [LearningCategoryController::class, 'toggleStatus'])->name('learning-cats.toggle');
    Route::delete('/learning-cats/{id}', [LearningCategoryController::class, 'destroy'])->name('learning-cats.destroy');

    Route::get('/learning-corner', [LearningCornerController::class, 'index'])->name('learning-corner.index');
    Route::get('/learning-corner/create', [LearningCornerController::class, 'create'])->name('learning-corner.create');
    Route::post('/learning-corner', [LearningCornerController::class, 'store'])->name('learning-corner.store');
    Route::get('/learning-corner/{id}/edit', [LearningCornerController::class, 'edit'])->name('learning-corner.edit');
    Route::put('/learning-corner/{id}', [LearningCornerController::class, 'update'])->name('learning-corner.update');
    Route::delete('/learning-corner/{id}', [LearningCornerController::class, 'destroy'])->name('learning-corner.destroy');

    Route::get('/memberships', [MembershipController::class, 'index'])->name('memberships.index');
    Route::get('/memberships/export', [MembershipController::class, 'export'])->name('memberships.export');
    Route::get('/memberships/create', [MembershipController::class, 'create'])->name('memberships.create');
    Route::post('/memberships', [MembershipController::class, 'store'])->name('memberships.store');
    Route::get('/memberships/{id}', [MembershipController::class, 'show'])->name('memberships.show');
    Route::get('/memberships/{id}/edit', [MembershipController::class, 'edit'])->name('memberships.edit');
    Route::put('/memberships/{id}', [MembershipController::class, 'update'])->name('memberships.update');
    Route::delete('/memberships/{id}', [MembershipController::class, 'destroy'])->name('memberships.destroy');

    Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('/contact-messages/{id}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::post('/contact-messages/{id}/mark-read', [ContactMessageController::class, 'markRead'])->name('contact-messages.markRead');
    Route::post('/contact-messages/{id}/mark-replied', [ContactMessageController::class, 'markReplied'])->name('contact-messages.markReplied');
    Route::delete('/contact-messages/{id}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    Route::get('/mails', [MailController::class, 'index'])->name('mails.index');
    Route::get('/mails/{id}', [MailController::class, 'show'])->name('mails.show');
    Route::post('/mails/{id}/toggle-read', [MailController::class, 'toggleRead'])->name('mails.toggleRead');
    Route::post('/mails/{id}/replies', [MailController::class, 'storeReply'])->name('mails.replies.store');
    Route::delete('/mails/{id}/replies/{replyId}', [MailController::class, 'destroyReply'])->name('mails.replies.destroy');
    Route::delete('/mails/{id}', [MailController::class, 'destroy'])->name('mails.destroy');

    Route::get('/districts', [DistrictController::class, 'index'])->name('districts.index');
    Route::post('/districts', [DistrictController::class, 'store'])->name('districts.store');
    Route::get('/districts/{id}/edit', [DistrictController::class, 'edit'])->name('districts.edit');
    Route::put('/districts/{id}', [DistrictController::class, 'update'])->name('districts.update');
    Route::post('/districts/{id}/toggle', [DistrictController::class, 'toggleStatus'])->name('districts.toggle');
    Route::delete('/districts/{id}', [DistrictController::class, 'destroy'])->name('districts.destroy');

    Route::get('/blocks', [BlockController::class, 'index'])->name('blocks.index');
    Route::post('/blocks', [BlockController::class, 'store'])->name('blocks.store');
    Route::get('/blocks/{id}/edit', [BlockController::class, 'edit'])->name('blocks.edit');
    Route::put('/blocks/{id}', [BlockController::class, 'update'])->name('blocks.update');
    Route::post('/blocks/{id}/toggle', [BlockController::class, 'toggleStatus'])->name('blocks.toggle');
    Route::delete('/blocks/{id}', [BlockController::class, 'destroy'])->name('blocks.destroy');

    Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/create', [ProgramController::class, 'create'])->name('programs.create');
    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs/{id}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{id}', [ProgramController::class, 'update'])->name('programs.update');
    Route::post('/programs/{id}/toggle', [ProgramController::class, 'toggleStatus'])->name('programs.toggle');
    Route::delete('/programs/{id}', [ProgramController::class, 'destroy'])->name('programs.destroy');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::put('/settings/social', [SettingsController::class, 'updateSocial'])->name('settings.social.update');

    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('users.destroy');
});
