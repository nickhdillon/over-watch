<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Priority;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class PrioritySwitcher extends Component
{
    public Model $model;

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
