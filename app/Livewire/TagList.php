<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class TagList extends Component
{
    public Project $project;

    #[Computed]
    public function tags(): Collection
    {
        return $this->project
            ->tags()
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.tag-list');
    }
}
