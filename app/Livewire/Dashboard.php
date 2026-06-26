<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Ticket;
use Livewire\Component;
use App\Models\Project;
use App\Models\RecentView;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Dashboard extends Component
{
    private function recentViewsFor(string $type): Collection
    {
        return RecentView::query()
            ->where('user_id', auth()->id())
            ->whereHasMorph('viewable', [$type])
            ->with([
                'viewable' => function (MorphTo $morphTo): void {
                    $morphTo->morphWith([
                        Project::class => ['owner'],
                        Ticket::class => ['assignee', 'project']
                    ]);
                }
            ])
            ->latest('last_viewed_at')
            ->limit(10)
            ->get()
            ->pluck('viewable');
    }

    #[Computed]
    public function recentProjects(): Collection
    {
        return $this->recentViewsFor(Project::class);
    }

    #[Computed]
    public function recentTickets(): Collection
    {
        return $this->recentViewsFor(Ticket::class);
    }

    public function render(): View
    {
        return view('livewire.dashboard');
    }
}
