<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\Status;
use App\Models\Project;
use App\Models\Release;
use App\Livewire\StatusSwitcher;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can update model priority', function () {
    $release = Release::factory()
        ->for(Project::factory()->create())
        ->create(['status' => Status::TO_DO]);

    livewire(StatusSwitcher::class, ['model' => $release])
        ->set('status', Status::IN_PROGRESS)
        ->assertHasNoErrors();

    expect($release->fresh()->status)->toBe(Status::IN_PROGRESS);
});

test('component can render', function () {
    livewire(StatusSwitcher::class, ['model' => Release::factory()->create()])
        ->assertHasNoErrors();
});
