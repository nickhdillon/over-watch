<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use App\Enums\ProjectRole;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\View\View;

class ProjectFormModal extends Component
{
    public string $name = '';

    public string $key = '';

    public ?string $url = null;

    public ?string $description = null;

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
            ],
            'url' => ['string', 'url', 'nullable'],
            'description' => ['string', 'nullable']
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
        
        $project = auth()->user()->ownedProjects()->create($this->validate());

        $project->users()->attach(auth()->id(), ['role' => ProjectRole::OWNER->value]);

        $this->redirectRoute('project.view', $project);
    }

    public function render(): View
    {
        return view('livewire.project-form-modal');
    }
}
