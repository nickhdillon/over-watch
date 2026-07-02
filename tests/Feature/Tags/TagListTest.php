<?php

declare(strict_types=1);

use App\Models\Tag;
use App\Models\User;
use App\Models\Project;
use App\Livewire\TagList;
use App\Enums\ProjectRole;
use function Pest\Livewire\livewire;

test('component can render', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Pure Finance',
    ]);

    $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

    Tag::factory()->for($project)->create();

    livewire(TagList::class, ['project' => Project::first()])
        ->assertSeeText('Bug')
        ->assertHasNoErrors();
});
