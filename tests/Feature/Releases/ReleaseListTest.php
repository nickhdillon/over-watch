<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Project;
use App\Models\Release;
use App\Enums\ProjectRole;
use App\Livewire\ReleaseList;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Pure Finance',
    ]);

    $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

    Release::factory()->for($user)->for($project)->create();
});

test('component can render with project', function () {
    livewire(ReleaseList::class, ['project' => Project::first()])
        ->assertSee(Release::first()->name)
        ->assertSee(Project::first()->key)
        ->assertHasNoErrors();
});

test('component can render with no project', function () {
    livewire(ReleaseList::class)
        ->assertSee(Release::first()->name)
        ->assertHasNoErrors();
});
