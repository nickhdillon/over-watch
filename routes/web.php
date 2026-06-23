<?php

use App\Livewire\ProjectView;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('projects/{project:slug}', ProjectView::class)->name('project');
});

require __DIR__.'/settings.php';
