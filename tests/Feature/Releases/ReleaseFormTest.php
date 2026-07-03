<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Project;
use App\Models\Release;
use App\Enums\ProjectRole;
use App\Livewire\ReleaseForm;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Pure Finance',
    ]);

    $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

    Release::factory()->for($user)->for($project)->create(['name' => 'MVP']);
});

it('can update a release', function () {
    livewire(ReleaseForm::class, [
        'project' => Project::first(),
        'release' => Release::first()
    ])
        ->set('name', 'Launch')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('project.release.view', [Project::first(), Release::first()]);
});

it('can delete a release', function () {
    livewire(ReleaseForm::class, [
        'project' => Project::first(),
        'release' => Release::first()
    ])
        ->call('delete')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('project.releases', Project::first());
});

test('component can render', function () {
    livewire(ReleaseForm::class, [
        'project' => Project::first(),
        'release' => Release::first()
    ])
        ->assertSet('project_id', 1)
        ->assertSet('name', Release::first()->name)
        ->assertHasNoErrors();
});
