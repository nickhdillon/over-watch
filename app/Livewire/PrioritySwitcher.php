<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Priority;
use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Contracts\View\View;

class PrioritySwitcher extends Component
{
    public Ticket $ticket;

    public Priority $priority;

    public function mount(): void
    {
        $this->priority = $this->ticket->priority;
    }

    public function updatedPriority(): void
    {
        $this->ticket->update(['priority' => $this->priority]);
    }

    public function render(): View
    {
        return view('livewire.priority-switcher');
    }
}
