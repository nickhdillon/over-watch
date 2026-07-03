<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\Status;
use App\Models\Ticket;
use App\Models\Project;
use App\Enums\ProjectRole;
use App\Livewire\TicketList;
use function Pest\Livewire\livewire;

beforeEach(function () {
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
        );
});

it('can update view with project', function () {
    $project = Project::first();

    livewire(TicketList::class, ['project' => $project, 'view' => 'grid'])
        ->set('view', 'list')
        ->assertRedirectToRoute('project.tickets', [
            'project' => $project,
            'view' => 'list'
        ]);
});

it('can update view with no project', function () {
    livewire(TicketList::class, ['view' => 'grid'])
        ->set('view', 'list')
        ->assertRedirectToRoute('tickets', ['view' => 'list']);
});

it('can update ticket order', function () {
    livewire(TicketList::class, ['view' => 'grid'])
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
    livewire(TicketList::class, ['view' => 'grid'])
        ->call('updateTicketGroupOrder', [
            [
                'order' => 1,
                'value' => Status::TO_DO,
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

test('component can render with project', function () {
    livewire(TicketList::class, ['project' => Project::first(), 'view' => 'list'])
        ->assertHasNoErrors();
});

test('component can render with no project', function () {
    livewire(TicketList::class, ['view' => 'grid'])
        ->assertHasNoErrors();
});
