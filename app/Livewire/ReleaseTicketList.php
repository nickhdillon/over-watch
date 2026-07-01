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

class ReleaseTicketList extends Component
{
    use WithPagination;
    use HandlesTicketReleases;

    public Release $release;

    #[Url(keep: true)]
    public string $view = 'list';

    /**
     * @return LengthAwarePaginator<int, Ticket>|Collection<int, Ticket>
     */
    #[Computed]
    public function tickets(): LengthAwarePaginator|Collection
    {
        /** @var \Illuminate\Database\Eloquent\Builder<Ticket> $tickets */
        $tickets = $this->release
            ->tickets()
            ->where('user_id', auth()->id())
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

    public function updatedView(string $view): void
    {
        $this->redirectRoute(
            'project.release.view',
            array_filter([
                'project' => $this->release->project,
                'release' => $this->release,
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
        return view('livewire.release-ticket-list');
    }
}
