<?php

use App\Livewire\TagList;
use App\Livewire\Dashboard;
use App\Livewire\TicketList;
use App\Livewire\ProjectView;
use App\Livewire\ProjectForm;
use App\Livewire\ProjectList;
use App\Livewire\ReleaseForm;
use App\Livewire\ReleaseList;
use App\Livewire\ReleaseView;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::get('projects/{project:slug}/settings', ProjectForm::class)->name('project.edit');
    Route::get('projects/{project:slug}', ProjectView::class)->name('project.view');
    Route::get('projects', ProjectList::class)->name('projects');

    Route::get('releases', ReleaseList::class)->name('releases');
    Route::get('projects/{project:slug}/releases', ReleaseList::class)->name('project.releases');
    Route::get('projects/{project:slug}/releases/{release:slug}', ReleaseView::class)->name('project.release.view');
    Route::get('projects/{project:slug}/releases/{release:slug}/settings', ReleaseForm::class)->name('project.release.edit');

    Route::get('tickets', TicketList::class)->name('tickets');
    Route::get('projects/{project:slug}/tickets', TicketList::class)->name('project.tickets');

    Route::get('projects/{project:slug}/tags', TagList::class)->name('project.tags');
});

require __DIR__.'/settings.php';
