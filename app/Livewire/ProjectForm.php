<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Color;
use App\Models\Project;
use Livewire\Component;
use App\Enums\ProjectRole;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProjectForm extends Component
{
    use WithFileUploads;

    public ?Project $project = null;

    public string $name = '';

    public ?string $description = null;

    public ?TemporaryUploadedFile $image = null;

    public ?string $image_path = null;

    public ?string $color = null;

    public function mount(): void
    {
        if ($this->project) {
            $this->name = $this->project->name;
            $this->description = $this->project->description;
            $this->image_path = $this->project->image_path;
            $this->color = $this->project->color?->value;
        } else {
            $this->color = Arr::random(Color::cases())->value;
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'description' => ['string', 'nullable'],
            'image' => ['file', 'max:12288', 'mimes:jpg,jpeg,png,heic,svg,avif,webp', 'nullable'],
            'image_path' => ['string', 'nullable'],
            'color' => ['nullable', Rule::enum(Color::class)]
        ];
    }

    public function save()
    {   
        $validated = $this->validate();

        $project = Project::updateOrCreate(
            ['id' => $this->project?->id],
            Arr::except($validated, ['image'])
        );

        if ($project->wasRecentlyCreated) {
            $project->users()->attach(auth()->id(), ['role' => ProjectRole::OWNER->value]);
        }

        if ($this->image instanceof TemporaryUploadedFile) {
            $project->update([
                'image_path' => $this->image->storePubliclyAs(
                    "projects/{$project->id}",
                    "image.{$this->image->getClientOriginalExtension()}",
                    's3',
                ),
            ]);
        }
    }

    public function render(): View
    {
        return view('livewire.project-form');
    }
}
