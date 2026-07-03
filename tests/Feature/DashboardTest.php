<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\Status;
use App\Models\Ticket;
use App\Models\Project;
use App\Models\Release;
use App\Enums\ProjectRole;
use App\Models\RecentView;
use App\Livewire\Dashboard;
use function Pest\Livewire\livewire;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('component can render with recently viewed projects, tickets, and releases', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Pure Finance',
    ]);

    $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

    Ticket::factory(2)
        ->for($project)
        ->for($user, 'assignee')
        ->sequence(
            [
                'name' => 'Test ticket',
                'slug' => 'test-ticket',
                'sequence' => 1,
                'status' => Status::TO_DO,
                'position' => 1
            ],
            [
                'name' => 'Test ticket 2',
                'slug' => 'test-ticket-2',
                'sequence' => 2,
                'status' => Status::TO_DO,
                'position' => 2
            ]
        )
        ->create();

    Release::factory()->for($user)->for($project)->create();

    foreach ([Project::first(), Ticket::first(), Release::first()] as $viewable) {
        RecentView::factory()
            ->for($user)
            ->for($viewable, 'viewable')
            ->create();
    }

    livewire(Dashboard::class)
        ->assertHasNoErrors();
});