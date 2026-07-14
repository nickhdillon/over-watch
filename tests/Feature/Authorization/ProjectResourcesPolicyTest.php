<?php

declare(strict_types=1);

use App\Enums\ProjectRole;
use App\Models\Project;
use App\Models\Release;
use App\Models\Tag;
use App\Models\Ticket;
use App\Models\User;

test('project members can access project resources', function () {
    $member = User::factory()->create();
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner, 'owner')->create();
    $project->users()->attach($member, ['role' => ProjectRole::MEMBER->value]);
    $ticket = Ticket::factory()->for($project)->create();
    $tag = Tag::factory()->for($project)->create();
    $release = Release::factory()->for($project)->create();

    expect($member->can('view', $project))->toBeTrue()
        ->and($member->can('view', $ticket))->toBeTrue()
        ->and($member->can('view', $tag))->toBeTrue()
        ->and($member->can('view', $release))->toBeTrue();
});

test('users cannot access resources belonging to another project', function () {
    $user = User::factory()->create();
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner, 'owner')->create();
    $ticket = Ticket::factory()->for($project)->create();
    $tag = Tag::factory()->for($project)->create();
    $release = Release::factory()->for($project)->create();

    expect($user->can('view', $project))->toBeFalse()
        ->and($user->can('view', $ticket))->toBeFalse()
        ->and($user->can('view', $tag))->toBeFalse()
        ->and($user->can('view', $release))->toBeFalse()
        ->and($user->can('update', $ticket))->toBeFalse()
        ->and($user->can('delete', $tag))->toBeFalse();
});

test('owners retain access without a project membership row', function () {
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner, 'owner')->create();

    expect($owner->can('view', $project))->toBeTrue()
        ->and($owner->can('update', $project))->toBeTrue()
        ->and($owner->can('delete', $project))->toBeTrue();
});

test('project routes reject users without access', function () {
    $user = User::factory()->create();
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner, 'owner')->create();

    $this->actingAs($user)
        ->get(route('project.view', $project))
        ->assertForbidden();
});

test('only a project owner can delete the project', function () {
    $member = User::factory()->create();
    $owner = User::factory()->create();
    $project = Project::factory()->for($owner, 'owner')->create();
    $project->users()->attach($member, ['role' => ProjectRole::MEMBER->value]);

    expect($member->can('update', $project))->toBeTrue()
        ->and($member->can('delete', $project))->toBeFalse();
});
