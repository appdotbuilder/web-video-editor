<?php

use App\Http\Controllers\VideoEditorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MediaFileController;
use App\Http\Controllers\TimelineClipController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', [VideoEditorController::class, 'index'])->name('home');
Route::get('/video-editor', [VideoEditorController::class, 'index'])->name('video-editor');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    
    // Project routes
    Route::post('projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::patch('projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    
    // Media file routes
    Route::post('media-files', [MediaFileController::class, 'store'])->name('media-files.store');
    Route::delete('media-files/{mediaFile}', [MediaFileController::class, 'destroy'])->name('media-files.destroy');
    
    // Timeline clip routes
    Route::post('timeline-clips', [TimelineClipController::class, 'store'])->name('timeline-clips.store');
    Route::patch('timeline-clips/{timelineClip}', [TimelineClipController::class, 'update'])->name('timeline-clips.update');
    Route::delete('timeline-clips/{timelineClip}', [TimelineClipController::class, 'destroy'])->name('timeline-clips.destroy');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
