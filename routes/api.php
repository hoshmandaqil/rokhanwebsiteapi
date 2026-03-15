<?php

use App\Http\Controllers\Api\V1\ActivityController;
use App\Http\Controllers\Api\V1\FacilityController;
use App\Http\Controllers\Api\V1\NewsController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('api.v1.')->group(function (): void {
    Route::get('facilities', [FacilityController::class, 'index'])->name('facilities.index');
    Route::get('facilities/{facility:slug}', [FacilityController::class, 'show'])->name('facilities.show');
    Route::get('news', [NewsController::class, 'index'])->name('news.index');
    Route::get('news/{news:slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('activities/{activity:slug}', [ActivityController::class, 'show'])->name('activities.show');
});
