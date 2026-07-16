<?php

declare(strict_types=1);

use App\Enums\ProjectRole;
use App\Livewire\ReleaseView;
use App\Models\Project;
use App\Models\Release;
use App\Models\Ticket;
use App\Models\User;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Pure Finance',
    ]);

    $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

    Release::factory()
        ->for($user)
        ->for($project)
        ->has(
            Ticket::factory(2)
                ->for($project)
                ->for($user, 'assignee')
                ->sequence(
                    [
                        'name' => 'Test ticket',
                        'slug' => 'test-ticket',
                        'sequence' => 1,
                    ],
                    [
                        'name' => 'Test ticket 2',
                        'slug' => 'test-ticket-2',
                        'sequence' => 2,
                    ]
                )
        )
        ->create();
});

test('component can render', function () {
    $release = Release::first();

    livewire(ReleaseView::class, ['release' => $release])
        ->assertSee($release->name)
        ->assertHasNoErrors();
});
