<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Priority;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class PrioritySwitcher extends Component
{
    use AuthorizesRequests;

    public Project|Ticket $model;

    public Priority $priority;

    public function mount(): void
    {
        $this->priority = $this->model->priority;
    }

    public function updatedPriority(): void
    {
        $this->authorize('update', $this->model);

        $this->model->update(['priority' => $this->priority]);
    }

    public function render(): View
    {
        return view('livewire.priority-switcher');
    }
}
