<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class ReleaseList extends Component
{
    public ?Project $project = null;

    #[Computed]
    public function releases(): Collection
    {
        $query = $this->project
            ? $this->project->releases()
            : auth()->user()->releases();

        return $query
            ->with('project')
            ->withCount('tickets')
            ->orderBy('position')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.release-list');
    }
}
