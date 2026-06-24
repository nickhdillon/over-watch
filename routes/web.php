<?php

use App\Livewire\ProjectView;
use App\Livewire\ProjectForm;
use App\Livewire\ProjectList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('projects/{project:slug}/settings', ProjectForm::class)->name('project.edit');
    Route::get('projects/{project:slug}', ProjectView::class)->name('project.view');
    Route::get('projects', ProjectList::class)->name('projects');
});

require __DIR__.'/settings.php';
