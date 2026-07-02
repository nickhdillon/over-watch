<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Ticket;
use Livewire\Component;
use App\Models\Project;
use App\Models\Release;
use Livewire\Attributes\Computed;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Dashboard extends Component
{
    private function recentViewsFor(string $type): Collection
    {
        return auth()
            ->user()
            ->recentViews()
            ->whereHasMorph('viewable', [$type])
            ->with([
                'viewable' => function ($morph_to): void {
                    /** @var MorphTo $morph_to */
                    $morph_to->morphWith([
                        Ticket::class => ['assignee', 'project', 'release'],
                        Release::class => ['project'],
                    ]);

                    $morph_to->morphWithCount([
                        Release::class => ['tickets'],
                    ]);
                }
            ])
            ->latest('last_viewed_at')
            ->limit(15)
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

    #[Computed]
    public function recentReleases(): Collection
    {
        return $this->recentViewsFor(Release::class);
    }

    public function render(): View
    {
        return view('livewire.dashboard');
    }
}
