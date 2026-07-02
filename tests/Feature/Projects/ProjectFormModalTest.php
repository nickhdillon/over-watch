<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Project;
use App\Livewire\ProjectFormModal;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can create a project', function () {
    $component = livewire(ProjectFormModal::class)
        ->set('name', 'Pure Finance')
        ->set('key', 'PURE')
        ->set('url', 'https://pure-finance.app')
        ->call('save')
        ->assertHasNoErrors();

    $component->assertRedirectToRoute('project.view', Project::first());

    $this->assertDatabaseCount('project_user', 1);
});

it('can reset the form', function () {
    livewire(ProjectFormModal::class)
        ->set('name', 'Pure Finance')
        ->set('key', 'PURE')
        ->set('url', 'https://pure-finance.app')
        ->call('resetForm')
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(ProjectFormModal::class)
        ->assertSeeText('New project')
        ->assertHasNoErrors();
});
