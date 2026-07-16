<?php

declare(strict_types=1);

use App\Enums\ProjectRole;
use App\Enums\Status;
use App\Livewire\ReleaseTicketList;
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
        )
        ->create();
});

it('loads board tickets', function () {
    $component = livewire(ReleaseTicketList::class, ['release' => Release::first(), 'view' => 'board']);

    expect($component->instance()->boardTickets())->toHaveCount(2);
});

it('groups board tickets by status', function () {
    $component = livewire(ReleaseTicketList::class, ['release' => Release::first(), 'view' => 'board']);

    $tickets_by_status = $component->instance()->ticketsByStatus();

    expect($tickets_by_status)
        ->toHaveKey(Status::OPEN->value)
        ->and($tickets_by_status->get(Status::OPEN->value))
        ->toHaveCount(2);
});

it('can update ticket order', function () {
    $release = Release::first();

    livewire(ReleaseTicketList::class, ['release' => $release, 'view' => 'board'])
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
    $release = Release::first();

    livewire(ReleaseTicketList::class, ['release' => $release, 'view' => 'board'])
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

it('renders a skeleton while list tickets load', function () {
    livewire(ReleaseTicketList::class, ['release' => Release::first(), 'view' => 'list'])
        ->assertSeeHtml('data-flux-skeleton-group')
        ->assertSeeHtml('data-flux-skeleton-line');
});

it('renders a skeleton while board tickets load', function () {
    livewire(ReleaseTicketList::class, ['release' => Release::first(), 'view' => 'board'])
        ->assertSeeHtml('data-flux-skeleton-group')
        ->assertSeeHtml('data-flux-skeleton-line');
});

it('resets pagination when the search is updated', function () {
    livewire(ReleaseTicketList::class, ['release' => Release::first(), 'view' => 'list'])
        ->call('setPage', 2)
        ->assertSet('paginators.page', 2)
        ->set('search', 'Test ticket')
        ->assertSet('paginators.page', 1);
});

it('applies filters to release tickets', function () {
    $tickets = Ticket::query()->orderBy('position')->get();
    $tickets[1]->update(['status' => Status::DONE]);

    $component = livewire(ReleaseTicketList::class, [
        'release' => Release::first(),
        'view' => 'board',
    ])
        ->set('draft_filters', [Status::DONE->value])
        ->assertSet('filters', [])
        ->call('applyFilters')
        ->assertSet('filters', [Status::DONE->value]);

    expect($component->instance()->boardTickets())
        ->toHaveCount(1)
        ->first()->id->toBe($tickets[1]->id);
});

test('component can render', function () {
    $release = Release::first();

    livewire(ReleaseTicketList::class, ['release' => $release, 'view' => 'board'])
        ->assertSee($release->name)
        ->assertHasNoErrors();
});

it('does not offer project or release filters inside a release', function () {
    $component = livewire(ReleaseTicketList::class, [
        'release' => Release::first(),
        'view' => 'list',
    ])->instance();

    expect($component->showProjectTicketFilter())->toBeFalse()
        ->and($component->showReleaseTicketFilter())->toBeFalse();
});
