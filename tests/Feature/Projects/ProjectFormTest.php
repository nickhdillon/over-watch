<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\Project;
use App\Enums\ProjectRole;
use App\Livewire\ProjectForm;
use Illuminate\Http\UploadedFile;
use function Pest\Livewire\livewire;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('s3');

    $user = User::factory()->create();

    $this->actingAs($user);

    $project = Project::factory()->create([
        'owner_id' => $user->id,
        'name' => 'Pure Finance',
    ]);

    $project->users()->attach($user->id, ['role' => ProjectRole::OWNER->value]);
});

it('can update a project', function () {
    $project = Project::first();

    $image = UploadedFile::fake()->image('image.jpg');

    Storage::disk('s3')->put("projects/{$project->id}", $image);

    livewire(ProjectForm::class, ['project' => $project])
        ->set('key', 'PURE')
        ->set('image', $image)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('project.edit', $project);
});

it('can delete an image', function () {
    $project = Project::first();

    $image = UploadedFile::fake()->image('image.jpg');

    $path = Storage::disk('s3')->put("projects/{$project->id}", $image);

    $project->update(['image_path' => $path]);

    livewire(ProjectForm::class, ['project' => $project])
        ->call('deleteImage')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('project.edit', $project);

    expect($project->fresh()->image_path)->toBe(null);
});

it('can delete a project', function () {
    livewire(ProjectForm::class, ['project' => Project::first()])
        ->call('delete')
        ->assertHasNoErrors()
        ->assertRedirectToRoute('projects');

    $this->assertDatabaseCount('projects', 0);
});

test('component can render', function () {
    livewire(ProjectForm::class, ['project' => Project::first()])
        ->assertSet('name', 'Pure Finance')
        ->assertHasNoErrors();
});
