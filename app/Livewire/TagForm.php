<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Color;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class TagForm extends Component
{
    use AuthorizesRequests;

    public bool $show_tag_form = false;

    public Project $project;

    public int $project_id;

    public ?Tag $tag = null;

    public Collection $projects;

    public ?string $name = '';

    public ?Color $color = null;

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'int'],
            'name' => ['string', 'required'],
            'color' => ['required', Rule::enum(Color::class)],
        ];
    }

    public function mount(): void
    {
        $this->project_id = $this->project->id;

        $this->getProjects();
    }

    public function getProjects(): self
    {
        $this->projects = auth()
            ->user()
            ->projects()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return $this;
    }

    #[On('load-tag')]
    public function loadTag(int $tag_id): void
    {
        $this->tag = Tag::findOrFail($tag_id);
        $this->authorize('view', $this->tag);
        $this->project_id = $this->tag->project_id;
        $this->name = $this->tag->name;
        $this->color = $this->tag->color;

        $this->show_tag_form = true;
    }

    public function resetForm(): void
    {
        $this->reset([
            'project_id',
            'tag',
            'name',
            'color',
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate();

        $project = Project::query()->findOrFail($this->project_id);
        $this->authorize($this->tag ? 'update' : 'create', $this->tag ?? [Tag::class, $project]);

        Tag::updateOrCreate(
            ['id' => $this->tag?->id],
            [...$validated],
        );

        $this->redirectRoute('project.tags', $this->project);
    }

    public function render(): View
    {
        return view('livewire.tag-form');
    }
}
