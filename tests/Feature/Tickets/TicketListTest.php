<?php

declare(strict_types=1);

use App\Enums\ProjectRole;
use App\Enums\Status;
use App\Livewire\TicketList;
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

    Ticket::factory(2)
        ->for($project)
        ->for($user, 'assignee')
        ->sequence(
            [
                'name' => 'Test ticket',
                'slug' => 'test-ticket',
                'sequence' => 1,
                'status' => Status::OPEN,
                'position' => 1,
            ],
            [
                'name' => 'Test ticket 2',
                'slug' => 'test-ticket-2',
                'sequence' => 2,
                'status' => Status::OPEN,
                'position' => 2,
            ]
        )
        ->create();
});

it('loads board tickets', function () {
    $component = livewire(TicketList::class, ['view' => 'board']);

    expect($component->instance()->boardTickets())->toHaveCount(2);
});

it('groups board tickets by status', function () {
    $component = livewire(TicketList::class, ['view' => 'board']);

    $tickets_by_status = $component->instance()->ticketsByStatus();

    expect($tickets_by_status)
        ->toHaveKey(Status::OPEN->value)
        ->and($tickets_by_status->get(Status::OPEN->value))
        ->toHaveCount(2);
});

it('can update ticket order', function () {
    livewire(TicketList::class, ['view' => 'board'])
        ->call('updateTicketOrder', [
            [
                'order' => 1,
                'value' => '2',
            ],
            [
                'order' => 2,
                'value' => '1',
            ],
        ])
        ->assertHasNoErrors();
});

it('can update ticket group order', function () {
    livewire(TicketList::class, ['view' => 'board'])
        ->call('updateTicketGroupOrder', [
            [
                'order' => 1,
                'value' => Status::OPEN,
                'items' => [
                    [
                        'order' => 1,
                        'value' => '2',
                    ],
                    [
                        'order' => 2,
                        'value' => '1',
                    ],
                ],
            ],
        ])
        ->assertHasNoErrors();
});

it('can add ticket to release', function () {
    $project = Project::first();

    $ticket = Ticket::first();

    $release = Release::factory()
        ->for($project)
        ->for(User::first())
        ->create();

    livewire(TicketList::class, ['project' => $project, 'view' => 'board'])
        ->call('addTicketsToRelease', [$ticket->id], $release->id, $release->name)
        ->assertHasNoErrors();

    expect($ticket->fresh()->release_id)->toBe($release->id);
});

it('can remove ticket from release', function () {
    $project = Project::first();

    $ticket = Ticket::first();

    $release = Release::factory()
        ->for($project)
        ->for(User::first())
        ->create();

    $ticket->update(['release_id' => $release->id]);

    livewire(TicketList::class, ['project' => $project, 'view' => 'board'])
        ->call('removeTicketsFromRelease', [$ticket->id])
        ->assertHasNoErrors();

    expect($ticket->fresh()->release_id)->toBe(null);
});

test('component can render with project', function () {
    livewire(TicketList::class, ['project' => Project::first(), 'view' => 'list'])
        ->assertHasNoErrors();
});

test('component can render with no project', function () {
    livewire(TicketList::class, ['view' => 'board'])
        ->assertHasNoErrors();
});

it('renders a skeleton while list tickets load', function () {
    livewire(TicketList::class, ['view' => 'list'])
        ->assertSeeHtml('data-flux-skeleton-group')
        ->assertSeeHtml('data-flux-skeleton-line');
});

it('renders a skeleton while board tickets load', function () {
    livewire(TicketList::class, ['view' => 'board'])
        ->assertSeeHtml('data-flux-skeleton-group')
        ->assertSeeHtml('data-flux-skeleton-line');
});
