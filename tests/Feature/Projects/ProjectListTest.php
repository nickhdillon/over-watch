<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Project;
use App\Enums\ProjectRole;
use App\Livewire\ProjectList;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can see project name', function () {
    $user = User::first();

    $project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Pure Finance',
    ]);

    $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

    livewire(ProjectList::class)
        ->assertSeeText('Pure Finance')
        ->assertHasNoErrors();
});

test('component can render', function () {
    livewire(ProjectList::class)
        ->assertHasNoErrors();
});
