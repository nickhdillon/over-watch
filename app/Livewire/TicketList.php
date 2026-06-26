<?php

declare(strict_types=1);

namespace App\Livewire;

use Flux\Flux;
use App\Models\Ticket;
use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class TicketList extends Component
{
    #[Url(keep: true)]
    public string $view = 'list';

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

    public function updatedView(string $view): void
    {
        $this->redirectRoute(
            $this->project ? 'project.tickets' : 'tickets',
            array_filter([
                'project' => $this->project,
                'view' => $view,
            ]),
            navigate: true
        );
    }

    public function delete(int $ticket_id): void
    {
        Ticket::find($ticket_id)->delete();

        Flux::toast(
            variant: 'success',
            text: 'Ticket successfully deleted',
        );

        $this->redirectRoute('tickets', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.ticket-list');
    }
}
