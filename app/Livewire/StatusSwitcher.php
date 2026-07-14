<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\Status;
use App\Models\Release;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class StatusSwitcher extends Component
{
    use AuthorizesRequests;

    public Release|Ticket $model;

    public Status $status;

    public function mount(): void
    {
        $this->status = $this->model->status;
    }

    public function updatedStatus(): void
    {
        $this->authorize('update', $this->model);

        $this->model->update([
            'status' => $this->status,
            'completed_at' => $this->status === Status::DONE ? now() : null,
        ]);

        $this->redirect(url()->previous(), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.status-switcher');
    }
}
