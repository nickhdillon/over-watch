<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Project;
use App\Models\Release;
use App\Enums\ProjectRole;
use App\Livewire\ReleaseFormModal;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Pure Finance',
    ]);

    $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);
});

it('can create a release', function () {
    livewire(ReleaseFormModal::class, ['project' => Project::first()])
        ->set('name', 'v1.0')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('project.release.view', [Project::first(), Release::first()]);
});

it('can reset the form', function () {
    livewire(ReleaseFormModal::class, ['project' => Project::first()])
        ->set('name', 'v3.0')
        ->call('resetForm')
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(ReleaseFormModal::class, ['project' => Project::first()])
        ->assertSet('project_id', 1)
        ->assertHasNoErrors();
});
