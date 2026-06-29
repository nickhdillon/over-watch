<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class TicketList extends Component
{
    use WithPagination;

    #[Url(keep: true)]
    public string $view = 'list';

    public ?Project $project = null;

    /**
     * @return LengthAwarePaginator<int, Ticket>|EloquentCollection<int, Ticket>
     */
    #[Computed]
    public function tickets(): LengthAwarePaginator|EloquentCollection
    {
        /** @var Builder<Ticket> $query */
        $query = $this->project
            ? $this->project->tickets()
            : auth()->user()->tickets();

        $tickets = $query
            ->with(['assignee', 'project', 'tags'])
            ->orderBy('position');

        return $this->view === 'list'
            ? $tickets->paginate(25)
            : $tickets->get();
    }

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

    public function updateTicketOrder(array $items): void
    {
        DB::transaction(function () use ($items): void {
            foreach ($items as $item) {
                Ticket::query()
                    ->whereKey($item['value'])
                    ->update(['position' => $item['order']]);
            }
        });
    }

    public function updateTicketGroupOrder(array $groups): void
    {
        DB::transaction(function () use ($groups): void {
            foreach ($groups as $group) {
                $status = $group['value'];

                foreach ($group['items'] as $item) {
                    Ticket::query()
                        ->whereKey($item['value'])
                        ->update([
                            'status' => $status,
                            'position' => $item['order']
                        ]);
                }
            }
        });
    }

    public function render(): View
    {
        return view('livewire.ticket-list');
    }
}
