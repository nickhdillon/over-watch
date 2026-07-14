<?php

use App\Livewire\Dashboard;
use App\Livewire\ProjectForm;
use App\Livewire\ProjectList;
use App\Livewire\ProjectView;
use App\Livewire\ReleaseForm;
use App\Livewire\ReleaseList;
use App\Livewire\ReleaseView;
use App\Livewire\TagList;
use App\Livewire\TicketList;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::view('/', 'pages.landing-page')->name('landing-page');
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    Route::get('projects/{project:slug}/settings', ProjectForm::class)->can('update', 'project')->name('project.edit');
    Route::get('projects/{project:slug}', ProjectView::class)->can('view', 'project')->name('project.view');
    Route::get('projects', ProjectList::class)->name('projects');

    Route::get('releases', ReleaseList::class)->name('releases');
    Route::get('projects/{project:slug}/releases', ReleaseList::class)->can('view', 'project')->name('project.releases');
    Route::get('projects/{project:slug}/releases/{release:slug}', ReleaseView::class)
        ->scopeBindings()
        ->can('view', 'release')
        ->name('project.release.view');
    Route::get('projects/{project:slug}/releases/{release:slug}/settings', ReleaseForm::class)
        ->scopeBindings()
        ->can('update', 'release')
        ->name('project.release.edit');

    Route::get('tickets', TicketList::class)->name('tickets');
    Route::get('projects/{project:slug}/tickets', TicketList::class)->can('view', 'project')->name('project.tickets');

    Route::get('projects/{project:slug}/tags', TagList::class)->can('view', 'project')->name('project.tags');
});

require __DIR__.'/settings.php';
