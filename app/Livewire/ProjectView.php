<?php

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\Project;
use Livewire\Component;
use App\Models\RecentView;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;

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
            ->whereHasMorph('viewable', [Ticket::class])
            ->where('viewable_id', $this->project->id)
            ->with('viewable')
            ->latest('last_viewed_at')
            ->limit(5)
            ->get()
            ->pluck('viewable');
    }

    public function render()
    {
        return view('livewire.project-view');
    }
}
