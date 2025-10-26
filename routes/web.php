<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TastingController;
use App\Http\Controllers\TastingRoundController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SnackController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SessionController;

// Public routes
Route::get('/', [TastingController::class, 'welcome'])->name('welcome');

// Participants dashboard
Route::get('/dashboard', [TastingController::class, 'participantsDashboard'])->name('participants.dashboard');

// Tasting session routes
Route::prefix('tasting')->group(function () {
    Route::post('/start', [TastingController::class, 'startSession'])->name('tasting.start');
    Route::post('/start-session', [TastingController::class, 'startTastingSession'])->name('tasting.start-session');
    Route::get('/session/{sessionId}', [TastingController::class, 'showSession'])->name('tasting.session');
    Route::post('/session/{sessionId}/review', [TastingController::class, 'submitReview'])->name('tasting.submit-review');
    Route::get('/complete/{sessionId}', [TastingController::class, 'complete'])->name('tasting.complete');
    Route::get('/session/{sessionId}/progress', [TastingController::class, 'sessionProgress'])->name('tasting.progress');
});

// Admin routes
Route::prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
    Route::get('/export/{type}', [AdminController::class, 'exportData'])->name('admin.export');
    Route::get('/participants', [AdminController::class, 'participants'])->name('admin.participants');
    
    // Resource routes
    Route::resource('categories', CategoryController::class)->names('admin.categories');
    Route::resource('snacks', SnackController::class)->names('admin.snacks');
    Route::resource('tasting-rounds', TastingRoundController::class)->names('admin.tasting-rounds');
    Route::resource('reviews', ReviewController::class)->names('admin.reviews');
    Route::resource('sessions', SessionController::class)->names('admin.sessions');
    
    // Additional routes
    Route::post('/tasting-rounds/{tastingRound}/activate', [TastingRoundController::class, 'activate'])->name('admin.tasting-rounds.activate');
    Route::get('/tasting-rounds/{tastingRound}/results', [TastingRoundController::class, 'results'])->name('admin.tasting-rounds.results');
    Route::post('/sessions/{session}/force-complete', [SessionController::class, 'forceComplete'])->name('admin.sessions.force-complete');
    Route::get('/reviews/export', [ReviewController::class, 'export'])->name('admin.reviews.export');
});