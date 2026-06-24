<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Ticket;
use Livewire\Component;
use App\Enums\TicketStatus;
use Illuminate\Contracts\View\View;

class StatusSwitcher extends Component
{
    public Ticket $ticket;

    public TicketStatus $status;

    public function mount(): void
    {
        $this->status = $this->ticket->status;
    }

    public function updatedStatus(): void
    {
        $this->ticket->update(['status' => $this->status]);
    }
    
    public function render(): View
    {
        return view('livewire.status-switcher');
    }
}
