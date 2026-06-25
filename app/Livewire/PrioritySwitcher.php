<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Ticket;
use App\Enums\Priority;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class PrioritySwitcher extends Component
{
    public Project|Ticket $model;

    public Priority $priority;

    public function mount(): void
    {
        $this->priority = $this->model->priority;
    }

    public function updatedPriority(): void
    {
        $this->model->update(['priority' => $this->priority]);
    }

    public function render(): View
    {
        return view('livewire.priority-switcher');
    }
}
