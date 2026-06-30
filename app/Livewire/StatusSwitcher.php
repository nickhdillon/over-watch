<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Status;
use App\Models\Ticket;
use Livewire\Component;
use App\Models\Release;
use Illuminate\Contracts\View\View;

class StatusSwitcher extends Component
{
    public Release|Ticket $model;

    public Status $status;

    public function mount(): void
    {
        $this->status = $this->model->status;
    }

    public function updatedStatus(): void
    {
        $this->model->update([
            'status' => $this->status,
            'completed_at' => $this->status === Status::DONE ? now() : null
        ]);

        $this->redirect(url()->previous(), navigate: true);
    }
    
    public function render(): View
    {
        return view('livewire.status-switcher');
    }
}
