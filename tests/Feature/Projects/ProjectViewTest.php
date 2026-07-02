<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Ticket;
use App\Models\Project;
use App\Enums\ProjectRole;
use App\Models\RecentView;
use App\Livewire\ProjectView;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Pure Finance',
    ]);

    $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

    $ticket = Ticket::factory()->create([
        'project_id' => $project->id,
        'user_id' => $user->id,
        'name' => 'Test ticket',
    ]);

    RecentView::factory()
        ->for($user)
        ->for($ticket, 'viewable')
        ->create();
});

test('component can render', function () {
    livewire(ProjectView::class, ['project' => Project::first()])
        ->assertSeeText(Ticket::first()->name)
        ->assertHasNoErrors();
});
