<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\User;
use App\Models\Ticket;
use Livewire\Component;
use App\Models\Project;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Livewire\Concerns\HandlesTicketReleases;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class TicketList extends Component
{
    use WithPagination;
    use HandlesTicketReleases;

    #[Url(keep: true)]
    public string $view = 'list';

    public ?Project $project = null;

    public string $search = '';

    /**
     * @return HasMany<Ticket, Project|User>
     */
    private function ticketQuery(): HasMany
    {
        return $this->project
            ? $this->project->tickets()
            : auth()->user()->tickets();
    }

    /**
     * @return LengthAwarePaginator<int, Ticket>
     */
    #[Computed]
    public function tickets(): LengthAwarePaginator
    {
        return $this->ticketQuery()
            ->with(['assignee', 'project', 'release'])
            ->search($this->search)
            ->orderBy('position')
            ->paginate(25);
    }

    /**
     * @return EloquentCollection<int, Ticket>
     */
    #[Computed]
    public function boardTickets(): EloquentCollection
    {
        return $this->ticketQuery()
            ->with(['assignee', 'project', 'release', 'tags'])
            ->search($this->search, include_tags: true)
            ->orderBy('status')
            ->orderBy('position')
            ->get();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function ticketsByStatus(): Collection
    {
        return $this->boardTickets()
            ->groupBy(fn (Ticket $ticket): string => $ticket->status->value);
    }

    #[Computed]
    public function releases(): Collection
    {
        return auth()
            ->user()
            ->releases()
            ->with('project')
            ->when(
                $this->project,
                fn (Builder $query): Builder => $query->whereBelongsTo($this->project)
            )
            ->orderBy('name')
            ->get();
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

        unset($this->boardTickets, $this->ticketsByStatus);

        $this->js('$wire.$island("board-tickets").$refresh()');
    }

    public function render(): View
    {
        return view('livewire.ticket-list');
    }
}
