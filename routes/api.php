<?php

use App\Http\Controllers\Api\V1\AboutController;
use App\Http\Controllers\Api\V1\ActivityController;
use App\Http\Controllers\Api\V1\AnnouncementController;
use App\Http\Controllers\Api\V1\CareerController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\FacilityController;
use App\Http\Controllers\Api\V1\FacultyController;
use App\Http\Controllers\Api\V1\HomePageController;
use App\Http\Controllers\Api\V1\NewsController;
use App\Http\Controllers\Api\V1\ProgramController;
use App\Http\Controllers\Api\V1\WebsiteBannerController;
use App\Http\Controllers\Api\V1\WebsiteSettingController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('facilities', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('facilities/{facility:slug}', [FacilityController::class, 'show'])->name('facilities.show');
    Route::get('faculties', [FacultyController::class, 'index'])->name('faculties.index');
    Route::get('faculties/{faculty}', [FacultyController::class, 'show'])->name('faculties.show');
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('activities/{activity:slug}', [ActivityController::class, 'show'])->name('activities.show');
    Route::get('announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('announcements/{announcement:slug}', [AnnouncementController::class, 'show'])->name('announcements.show');
    Route::get('careers', [CareerController::class, 'index'])->name('careers.index');
    Route::get('careers/{career:slug}', [CareerController::class, 'show'])->name('careers.show');
    Route::get('home-page', [HomePageController::class, 'index'])->name('home-page.index');
    Route::get('website-banners', [WebsiteBannerController::class, 'index'])->name('website-banners.index');
    Route::get('website-settings', [WebsiteSettingController::class, 'index'])->name('website-settings.index');
    Route::get('abouts', [AboutController::class, 'index'])->name('abouts.index');
    Route::get('abouts/{slug}', [AboutController::class, 'show'])->name('abouts.show');
    Route::get('events', [EventController::class, 'index'])->name('events.index');
    Route::get('events/{event:slug}', [EventController::class, 'show'])->name('events.show');
    Route::get('programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::get('programs/{identifier}', [ProgramController::class, 'show'])->name('programs.show');
});
