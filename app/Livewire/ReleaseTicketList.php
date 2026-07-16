<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Ticket;
use Livewire\Component;
use App\Models\Project;
use App\Models\Release;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Livewire\Concerns\HandlesTicketReleases;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReleaseTicketList extends Component
{
    use WithPagination;
    use HandlesTicketReleases;

    public Release $release;

    #[Url(keep: true)]
    public string $view = 'list';

    public string $search = '';

    /**
     * @return HasMany<Ticket, Release>
     */
    private function ticketQuery(): HasMany
    {
        return $this->release
            ->tickets()
            ->where('user_id', auth()->id())
            ->with(['assignee', 'project'])
            ->orderBy('position');
    }

    /**
     * @return LengthAwarePaginator<int, Ticket>
     */
    #[Computed]
    public function tickets(): LengthAwarePaginator
    {
        return $this->ticketQuery()->search($this->search)->paginate(25);
    }

    /**
     * @return Collection<int, Ticket>
     */
    #[Computed]
    public function boardTickets(): Collection
    {
        return $this->ticketQuery()
            ->with('tags')
            ->search($this->search, include_tags: true)
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
        /** @var Project $project */
        $project = $this->release->project;

        return $project
            ->releases()
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
        return view('livewire.release-ticket-list');
    }
}
