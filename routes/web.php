<?php

use App\Livewire\ProjectView;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::view('/', 'dashboard')->name('dashboard');

    Route::get('projects/{project:slug}', ProjectView::class)->name('project');
});

require __DIR__.'/settings.php';
