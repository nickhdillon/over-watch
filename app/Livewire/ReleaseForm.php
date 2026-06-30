<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Project;
use App\Models\Release;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;

class ReleaseForm extends Component
{
    public Release $release;

    public Collection $projects;
    
    public Project $project;

    public ?int $project_id = null;

    public string $name = '';

    public ?string $description = null;

    public ?Carbon $start_date = null;

    public ?Carbon $due_date = null;

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'int'],
            'name' => ['string', 'required'],
            'description' => ['string', 'nullable'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date']
        ];
    }

    public function mount(): void
    {
        $this->project_id = $this->release->project_id;
        $this->name = $this->release->name;
        $this->description = $this->release->description;
        $this->start_date = $this->release->start_date;
        $this->due_date = $this->release->due_date;

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

    public function save(): void
    {
        $this->release->update($this->validate());

        $this->redirectRoute('project.release.view', [$this->project, $this->release]);
    }

    public function delete(): void
    {
        $this->release->delete();

        $this->redirectRoute('project.releases', $this->project);
    }
    
    public function render(): View
    {
        return view('livewire.release-form');
    }
}
