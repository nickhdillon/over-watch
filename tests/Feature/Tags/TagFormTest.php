<?php

declare(strict_types=1);

use App\Models\Tag;
use App\Enums\Color;
use App\Models\User;
use App\Models\Project;
use App\Livewire\TagForm;
use App\Enums\ProjectRole;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Pure Finance',
    ]);

    $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

    Tag::factory()->for($project)->create();
});

it('can create a tag', function () {
    livewire(TagForm::class, ['project' => Project::first()])
        ->set('name', 'Backend')
        ->set('color', Color::INDIGO)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('project.tags', Project::first());
});

it('can update a tag', function () {
    livewire(TagForm::class, ['project' => Project::first()])
        ->call('loadTag', Tag::first()->id)
        ->set('name', 'Feature')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('project.tags', Project::first());
});

it('can reset the form', function () {
    livewire(TagForm::class, ['project' => Project::first()])
        ->set('name', 'v3.0')
        ->call('resetForm')
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(TagForm::class, ['project' => Project::first()])
        ->assertSet('project_id', 1)
        ->assertHasNoErrors();
});
