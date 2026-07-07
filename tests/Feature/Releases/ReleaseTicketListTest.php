<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\Status;
use App\Models\Ticket;
use App\Models\Project;
use App\Models\Release;
use App\Enums\ProjectRole;
use App\Livewire\ReleaseTicketList;
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
                        'status' => Status::OPEN,
                        'position' => 1
                    ],
                    [
                        'name' => 'Test ticket 2',
                        'slug' => 'test-ticket-2',
                        'sequence' => 2,
                        'status' => Status::OPEN,
                        'position' => 2
                    ]
                )
            )
        ->create();
});

it('can update view', function () {
    $release = Release::first();

    livewire(ReleaseTicketList::class, ['release' => $release, 'view' => 'board'])
        ->set('view', 'list')
        ->assertRedirectToRoute('project.release.view', [
            'project' => $release->project,
            'release' => $release,
            'view' => 'list'
        ]);
});

it('can update ticket order', function () {
    $release = Release::first();

    livewire(ReleaseTicketList::class, ['release' => $release, 'view' => 'board'])
        ->call('updateTicketOrder', [
            [
                'order' => 1,
                'value' => '2'
            ],
            [
                'order' => 2,
                'value' => '1'
            ]
        ])
        ->assertHasNoErrors();
});

it('can update ticket group order', function () {
    $release = Release::first();

    livewire(ReleaseTicketList::class, ['release' => $release, 'view' => 'board'])
        ->call('updateTicketGroupOrder', [
            [
                'order' => 1,
                'value' => Status::OPEN,
                'items' => [
                    [
                        'order' => 1,
                        'value' => '2'
                    ],
                    [
                        'order' => 2,
                        'value' => '1'
                    ]
                ]
            ]
        ])
        ->assertHasNoErrors();
});

test('component can render', function () {
    $release = Release::first();

    livewire(ReleaseTicketList::class, ['release' => $release, 'view' => 'board'])
        ->assertSee($release->name)
        ->assertHasNoErrors();
});
