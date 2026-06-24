<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class TicketList extends Component
{
    public ?Project $project = null;

    #[Computed]
    public function tickets(): Collection
    {
        $query = $this->project
            ? $this->project->tickets()
            : auth()->user()->tickets();

        return $query
            ->with(['assignee', 'project'])
            ->orderByPriority()
            ->get();
    }

    public function render(): View
    {
        return view('livewire.ticket-list');
    }
}
