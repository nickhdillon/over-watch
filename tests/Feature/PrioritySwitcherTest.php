<?php

declare(strict_types=1);

use App\Models\User;
use App\Enums\Priority;
use App\Models\Project;
use App\Livewire\PrioritySwitcher;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('can update model priority', function () {
    $project = Project::factory()->create(['priority' => Priority::LOW]);

    livewire(PrioritySwitcher::class, ['model' => $project])
        ->set('priority', Priority::HIGH)
        ->assertHasNoErrors();

    expect($project->fresh()->priority)->toBe(Priority::HIGH);
});

test('component can render', function () {
    livewire(PrioritySwitcher::class, ['model' => Project::factory()->create()])
        ->assertHasNoErrors();
});
