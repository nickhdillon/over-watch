<?php

declare(strict_types=1);

use App\Enums\Priority;
use App\Enums\ProjectRole;
use App\Enums\Status;
use App\Livewire\TicketList;
use App\Models\Project;
use App\Models\Release;
use App\Models\Tag;
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

it('updates completion when moving tickets between status groups', function () {
    $ticket = Ticket::first();

    livewire(TicketList::class, ['view' => 'board'])
        ->call('updateTicketGroupOrder', [[
            'order' => 1,
            'value' => Status::DONE->value,
            'items' => [[
                'order' => 1,
                'value' => (string) $ticket->id,
            ]],
        ]])
        ->assertHasNoErrors();

    expect($ticket->refresh()->completed_at)->not->toBeNull();

    livewire(TicketList::class, ['view' => 'board'])
        ->call('updateTicketGroupOrder', [[
            'order' => 1,
            'value' => Status::OPEN->value,
            'items' => [[
                'order' => 1,
                'value' => (string) $ticket->id,
            ]],
        ]])
        ->assertHasNoErrors();

    expect($ticket->refresh()->completed_at)->toBeNull();
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

it('resets pagination when the search is updated', function () {
    livewire(TicketList::class, ['view' => 'list'])
        ->call('setPage', 2)
        ->assertSet('paginators.page', 2)
        ->set('search', 'Test ticket')
        ->assertSet('paginators.page', 1);
});

it('sorts tickets by name, priority, and status in both directions', function (string $sort, string $direction, array $expected_names) {
    $tickets = Ticket::query()->orderBy('position')->get();
    $tickets[0]->update([
        'name' => 'Zulu',
        'priority' => Priority::MEDIUM,
        'status' => Status::OPEN,
    ]);
    $tickets[1]->update([
        'name' => 'Alpha',
        'priority' => Priority::HIGH,
        'status' => Status::DONE,
    ]);

    $component = livewire(TicketList::class, ['view' => 'list'])
        ->set('sort', $sort)
        ->set('sort_direction', $direction);

    expect($component->instance()->tickets()->pluck('name')->all())->toBe($expected_names);
})->with([
    'name A-Z' => ['name', 'asc', ['Alpha', 'Zulu']],
    'name Z-A' => ['name', 'desc', ['Zulu', 'Alpha']],
    'priority A-Z' => ['priority', 'asc', ['Alpha', 'Zulu']],
    'priority Z-A' => ['priority', 'desc', ['Zulu', 'Alpha']],
    'status A-Z' => ['status', 'asc', ['Alpha', 'Zulu']],
    'status Z-A' => ['status', 'desc', ['Zulu', 'Alpha']],
]);

it('sorts by project and project key', function (string $sort, string $direction, array $expected_names) {
    $user = User::first();
    $first_project = Project::first();
    $first_project->update([
        'name' => 'Zulu Project',
        'key' => 'ZULU',
    ]);

    $second_project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Alpha Project',
        'key' => 'ALPHA',
    ]);
    $second_project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

    Ticket::factory()->for($second_project)->for($user, 'assignee')->create([
        'name' => 'Alpha project ticket',
        'sequence' => 3,
    ]);

    $component = livewire(TicketList::class, ['view' => 'list'])
        ->set('sort', $sort)
        ->set('sort_direction', $direction);

    expect($component->instance()->tickets()->pluck('name')->all())->toBe($expected_names);
})->with([
    'project A-Z' => ['project', 'asc', ['Alpha project ticket', 'Test ticket', 'Test ticket 2']],
    'project Z-A' => ['project', 'desc', ['Test ticket', 'Test ticket 2', 'Alpha project ticket']],
    'project key ascending' => ['key', 'asc', ['Alpha project ticket', 'Test ticket', 'Test ticket 2']],
    'project key descending' => ['key', 'desc', ['Test ticket 2', 'Test ticket', 'Alpha project ticket']],
]);

it('rejects unsupported ticket sorts', function () {
    livewire(TicketList::class, ['view' => 'list'])
        ->set('sort', 'assignee')
        ->assertSet('sort', 'position')
        ->set('sort_direction', 'sideways')
        ->assertSet('sort_direction', 'asc');
});

it('shows a sort summary only when the default sort changes', function () {
    livewire(TicketList::class, ['view' => 'list'])
        ->assertSeeText('Sort')
        ->assertDontSeeText('Position · Asc')
        ->set('sort_direction', 'desc')
        ->assertSeeText('Position · Desc')
        ->set('sort', 'name')
        ->assertSeeText('Name · Z–A')
        ->set('sort_direction', 'asc')
        ->assertSeeText('Name · A–Z');
});

it('resets ticket sorting to position ascending', function () {
    livewire(TicketList::class, ['view' => 'list'])
        ->assertDontSeeText('Reset')
        ->set('sort', 'name')
        ->set('sort_direction', 'desc')
        ->assertSeeText('Reset')
        ->call('resetTicketSort')
        ->assertSet('sort', 'position')
        ->assertSet('sort_direction', 'asc')
        ->assertDontSeeText('Reset');
});

it('only applies draft filters after apply is called', function () {
    $component = livewire(TicketList::class, ['view' => 'list'])
        ->set('draft_filters', [Status::DONE->value])
        ->assertSet('filters', []);

    expect($component->instance()->tickets())->toHaveCount(2);

    $component
        ->call('applyFilters')
        ->assertSet('filters', [Status::DONE->value]);

    expect($component->instance()->tickets())->toHaveCount(0);
});

it('syncs draft filters with the applied filters', function () {
    livewire(TicketList::class, ['view' => 'list'])
        ->set('draft_filters', [Status::OPEN->value, Priority::HIGH->value])
        ->call('applyFilters')
        ->set('draft_filters', [Status::DONE->value])
        ->assertSet('filters', [Status::OPEN->value, Priority::HIGH->value])
        ->assertSet('draft_filters', [Status::DONE->value])
        ->call('syncDraftFilters')
        ->assertSet('filters', [Status::OPEN->value, Priority::HIGH->value])
        ->assertSet('draft_filters', [Status::OPEN->value, Priority::HIGH->value]);
});

it('filters tickets by status and priority', function () {
    $tickets = Ticket::query()->orderBy('position')->get();

    $tickets[0]->update(['priority' => Priority::HIGH]);
    $tickets[1]->update([
        'status' => Status::DONE,
        'priority' => Priority::LOW,
    ]);

    $component = livewire(TicketList::class, ['view' => 'board'])
        ->set('draft_filters', [Status::OPEN->value, Priority::HIGH->value])
        ->call('applyFilters');

    expect($component->instance()->boardTickets())
        ->toHaveCount(1)
        ->first()->id->toBe($tickets[0]->id);
});

it('filters tickets by tag, project, and release', function () {
    $user = User::first();
    $project = Project::first();
    $matching_ticket = Ticket::first();
    $tag = Tag::factory()->for($project)->create(['name' => 'Backend']);
    $release = Release::factory()->for($user)->for($project)->create(['name' => 'MVP']);

    $matching_ticket->tags()->attach($tag);
    $matching_ticket->update(['release_id' => $release->id]);

    $component = livewire(TicketList::class, ['view' => 'board'])
        ->set('draft_filters', ["tag:{$tag->name}", "project:{$project->id}", "release:{$release->id}"])
        ->call('applyFilters');

    expect($component->instance()->boardTickets())
        ->toHaveCount(1)
        ->first()->id->toBe($matching_ticket->id);
});

it('lists each tag name once and filters matching tags across projects', function () {
    $user = User::first();
    $first_project = Project::first();
    $second_project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Other Project',
        'key' => 'OTHER',
    ]);
    $second_project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

    $first_tag = Tag::factory()->for($first_project)->create(['name' => 'Backend']);
    $second_tag = Tag::factory()->for($second_project)->create(['name' => 'Backend']);
    Ticket::first()->tags()->attach($first_tag);
    $second_ticket = Ticket::factory()->for($second_project)->for($user, 'assignee')->create();
    $second_ticket->tags()->attach($second_tag);

    $component = livewire(TicketList::class, ['view' => 'board']);

    expect($component->instance()->ticketFilterTags()->pluck('name')->all())
        ->toBe(['Backend']);

    $component
        ->set('draft_filters', ['tag:Backend'])
        ->call('applyFilters');

    expect($component->instance()->boardTickets())->toHaveCount(2);
});

it('only offers project and release filters when they are not already scoped', function () {
    $project = Project::first();

    $unscoped = livewire(TicketList::class, ['view' => 'list'])->instance();
    $project_scoped = livewire(TicketList::class, ['project' => $project, 'view' => 'list'])->instance();

    expect($unscoped->showProjectTicketFilter())->toBeTrue()
        ->and($unscoped->showReleaseTicketFilter())->toBeTrue()
        ->and($project_scoped->showProjectTicketFilter())->toBeFalse()
        ->and($project_scoped->showReleaseTicketFilter())->toBeTrue();
});

it('can remove and clear applied filters', function () {
    livewire(TicketList::class, ['view' => 'list'])
        ->set('draft_filters', [Status::OPEN->value, Priority::HIGH->value])
        ->call('applyFilters')
        ->call('removeFilter', Status::OPEN->value)
        ->assertSet('filters', [Priority::HIGH->value])
        ->call('clearFilters')
        ->assertSet('filters', [])
        ->assertSet('draft_filters', []);
});

test('component can render with project', function () {
    livewire(TicketList::class, ['project' => Project::first(), 'view' => 'list'])
        ->assertHasNoErrors();
});

test('component can render with no project', function () {
    livewire(TicketList::class, ['view' => 'board'])
        ->assertHasNoErrors();
});
