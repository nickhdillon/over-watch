<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Color;
use App\Enums\Priority;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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

    public string $key = '';

    public ?string $url = null;

    public ?string $repository_url = null;

    public ?string $description = null;

    public ?TemporaryUploadedFile $image = null;

    public ?string $image_path = null;

    public ?Color $color = null;

    public Priority $priority = Priority::MEDIUM;

    public function mount(): void
    {
        $this->name = $this->project->name;
        $this->key = $this->project->key;
        $this->url = $this->project->url;
        $this->repository_url = $this->project->repository_url;
        $this->description = $this->project->description;
        $this->image_path = $this->project->image_path;
        $this->color = $this->project->color;
        $this->priority = $this->project->priority;
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'key' => [
                'required',
                'string',
                'min:3',
                'max:4',
                'alpha_num',
                'uppercase',
                Rule::unique('projects', 'key')
                    ->where('owner_id', auth()->id())
                    ->ignore($this->project)
            ],
            'url' => ['string', 'url', 'nullable'],
            'repository_url' => ['string', 'url', 'nullable'],
            'description' => ['string', 'nullable'],
            'image' => ['file', 'max:12288', 'mimes:jpg,jpeg,png,heic,svg,avif,webp', 'nullable'],
            'image_path' => ['string', 'nullable'],
            'color' => ['nullable', Rule::enum(Color::class)],
            'priority' => ['required', Rule::enum(Priority::class)],
        ];
    }

    protected function normalizeKey(): void
    {
        $this->key = Str::of($this->key)
            ->upper()
            ->replaceMatches('/[^A-Z0-9]/', '')
            ->substr(0, 4)
            ->toString();
    }

    public function save(): void
    {
        $this->normalizeKey();
        
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

    public function delete(): void
    {
        $this->project->delete();

        $this->redirectRoute('projects');
    }

    public function render(): View
    {
        return view('livewire.project-form');
    }
}
