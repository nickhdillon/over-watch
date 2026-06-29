<?php

declare(strict_types=1);

namespace App\Livewire;

use Flux\Flux;
use App\Models\Ticket;
use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class TicketList extends Component
{
    #[Url(keep: true)]
    public string $view = 'list';

    public ?Project $project = null;

    /**
     * @return EloquentCollection<int, Ticket>
    */
    #[Computed]
    public function tickets(): EloquentCollection
    {
        $query = $this->project
            ? $this->project->tickets()
            : auth()->user()->tickets();

        return $query
            ->with(['assignee', 'project', 'tags'])
            ->orderByPriority()
            ->get();
    }

    /**
     * @return Collection<string, EloquentCollection<int, Ticket>>
     */
    #[Computed]
    public function ticketsByStatus(): Collection
    {
        return $this->tickets()->groupBy(fn (Ticket $ticket): string => $ticket->status->value);
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
