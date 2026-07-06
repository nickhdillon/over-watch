<?php

declare(strict_types=1);

use App\Models\Tag;
use App\Models\User;
use App\Enums\Status;
use App\Models\Ticket;
use App\Models\Project;
use App\Models\Release;
use App\Enums\ProjectRole;
use App\Livewire\TicketForm;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Pure Finance',
    ]);

    $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);

    Release::factory()->for($user)->for($project)->create();

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
        ->create();

    Tag::factory()->for($project)->create();
});

it('can create a ticket', function () {
    $project = Project::first();

    livewire(TicketForm::class, ['project' => $project])
        ->set('project_id', 1)
        ->set('name', 'Finish MVP')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect("/projects/{$project->slug}/tickets?view=list");
});

it('can update a ticket', function () {
    $project = Project::first();

    livewire(TicketForm::class, ['project' => $project])
        ->call('loadTicket', Ticket::first()->id)
        ->set('name', 'Test ticket updated')
        ->set('tags', [Tag::first()->id])
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect("/projects/{$project->slug}/tickets?view=list");
});

it('can update a ticket attached to a release', function () {
    $project = Project::first();

    $release = Release::first();
    
    livewire(TicketForm::class, ['project' => $project, 'release' => $release])
        ->call('loadTicket', Ticket::first()->id)
        ->set('name', 'Test ticket updated')
        ->set('tags', [Tag::first()->id])
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect("/projects/{$project->slug}/releases/{$release->slug}?view=list");
});

it('can remove a tag', function () {
    $project = Project::first();

    $tag = Tag::first();

    livewire(TicketForm::class, ['project' => $project])
        ->call('loadTicket', Ticket::first()->id)
        ->set('name', 'Test ticket updated')
        ->set('tags', [$tag->id])
        ->call('removeTag', $tag->name)
        ->assertHasNoErrors();
});

it('can reset the form', function () {
    livewire(TicketForm::class)
        ->call('resetForm')
        ->assertSet('project_id', null)
        ->assertHasNoErrors();
});

it('can reset the form with project', function () {
    livewire(TicketForm::class, ['project' => Project::first()])
        ->call('resetForm')
        ->assertSet('name', '')
        ->assertHasNoErrors();
});

it('can delete a ticket', function () {
    livewire(TicketForm::class, ['ticket' => Ticket::first(), 'view' => 'list'])
        ->call('delete')
        ->assertHasNoErrors()
        ->assertRedirect('/tickets?view=list');

    $this->assertDatabaseCount('tickets', 1);
});

test('component can render', function () {
    livewire(TicketForm::class)
        ->assertHasNoErrors();
});
