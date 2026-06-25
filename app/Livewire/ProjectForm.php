<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Color;
use App\Enums\Priority;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProjectForm extends Component
{
    use WithFileUploads;

    public Project $project;

    public string $name = '';

    public ?string $url = null;

    public ?string $description = null;

    public ?TemporaryUploadedFile $image = null;

    public ?string $image_path = null;

    public ?Color $color = null;

    public Priority $priority = Priority::MEDIUM;

    public function mount(): void
    {
        $this->name = $this->project->name;
        $this->url = $this->project->url;
        $this->description = $this->project->description;
        $this->image_path = $this->project->image_path;
        $this->color = $this->project->color;
        $this->priority = $this->project->priority;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'url' => ['string', 'url', 'nullable'],
            'description' => ['string', 'nullable'],
            'image' => ['file', 'max:12288', 'mimes:jpg,jpeg,png,heic,svg,avif,webp', 'nullable'],
            'image_path' => ['string', 'nullable'],
            'color' => ['nullable', Rule::enum(Color::class)],
            'priority' => ['required', Rule::enum(Priority::class)],
        ];
    }

    public function save(): void
    {
        $this->project->update(Arr::except($this->validate(), ['image']));

        if ($this->image instanceof TemporaryUploadedFile) {
            $this->project->update([
                'image_path' => $this->image->storePubliclyAs(
                    "projects/{$this->project->id}",
                    "image.{$this->image->getClientOriginalExtension()}",
                    's3',
                ),
            ]);
        }

        $this->redirectRoute('project.edit', $this->project);
    }

    public function deleteImage(): void
    {
        Storage::disk('s3')->delete($this->image_path);

        $this->project->update(['image_path' => null]);

        $this->redirectRoute('project.edit', $this->project);
    }

    public function render(): View
    {
        return view('livewire.project-form');
    }
}
