<?php

use App\Livewire\ProjectView;
use App\Livewire\ProjectForm;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::prefix('projects/')->group(function () {
        Route::get('create', ProjectForm::class)->name('project.create');
        Route::get('{project}/edit', ProjectForm::class)->name('project.edit');
        Route::get('{project:slug}', ProjectView::class)->name('project.view');
    });
});

require __DIR__.'/settings.php';
