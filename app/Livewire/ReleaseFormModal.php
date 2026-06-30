<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Status;
use App\Models\Project;
use App\Models\Release;
use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;

class ReleaseFormModal extends Component
{
    public Collection $projects;

    public ?Project $project = null;

    public int $project_id;

    public string $name = '';

    public ?string $description = null;

    public ?Carbon $start_date = null;

    public ?Carbon $due_date = null;

    public function rules(): array
    {
        return [
            'project_id' => ['int', 'required'],
            'name' => ['string', 'required'],
            'description' => ['string', 'nullable'],
            'start_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date']
        ];
    }

    public function mount(): void
    {
        if ($this->project) {
            $this->project_id = $this->project->id;
        }

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

    public function resetForm(): void
    {
        $this->reset([
            'project_id',
            'name',
            'description',
            'start_date',
            'due_date'
        ]);
    }

    public function save(): void
    {
        $release = Release::create([
            ...$this->validate(),
            'user_id' => auth()->id(),
            'project_id' => $this->project_id,
            'status' => Status::TO_DO
        ]);

        $this->redirectRoute('project.release.view', [$this->project ?? $release->project, $release]);
    }

    public function render(): View
    {
        return view('livewire.release-form-modal');
    }
}
