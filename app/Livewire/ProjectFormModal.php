<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use App\Enums\ProjectRole;
use Livewire\Attributes\Validate;
use Illuminate\Contracts\View\View;

class ProjectFormModal extends Component
{
    #[Validate(['string', 'required'])]
    public string $name = '';

    #[Validate(['string', 'url', 'nullable'])]
    public ?string $url = null;

    #[Validate(['string', 'nullable'])]
    public ?string $description = null;

    public function save(): void
    {
        $project = Project::create($this->validate());

        $project->users()->attach(auth()->id(), ['role' => ProjectRole::OWNER->value]);

        $this->redirectRoute('project.view', $project);
    }

    public function render(): View
    {
        return view('livewire.project-form-modal');
    }
}
