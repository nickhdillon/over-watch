<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\Project;
use Livewire\Component;
use App\Models\RecentView;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ProjectView extends Component
{
    public Project $project;

    public function mount(): void
    {
        $this->project->trackRecentView();
    }

    #[Computed]
    public function recentTickets(): Collection
    {
        return RecentView::query()
            ->where('user_id', auth()->id())
            ->whereHasMorph(
                'viewable',
                [Ticket::class],
                fn (Builder $query): Builder => $query->where('project_id', $this->project->id)
            )
            ->with([
                'viewable' => function (MorphTo $morphTo): void {
                    $morphTo->morphWith([Ticket::class => ['assignee', 'project']]);
                }
            ])
            ->latest('last_viewed_at')
            ->limit(5)
            ->get()
            ->pluck('viewable');
    }

    public function render(): View
    {
        return view('livewire.project-view');
    }
}
