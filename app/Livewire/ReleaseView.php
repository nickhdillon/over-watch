<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Project;
use App\Models\Release;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ReleaseView extends Component
{
    public ?Project $project = null;

    public Release $release;

    public function mount(): void
    {
        $this->release->trackRecentView();
    }

    #[Computed]
    public function stats(): array
    {
        $stats = $this->release
            ->tickets()
            ->where('user_id', auth()->id())
            ->selectRaw('
                COUNT(*) as total,
                SUM(completed_at IS NOT NULL) as completed
            ')
            ->first();

        $total = (int) $stats['total'];
        $completed = (int) $stats['completed'];

        return [
            'total' => $total,
            'completed' => $completed,
            'remaining' => max($total - $completed, 0),
            'progress' => $total
                ? (int) round(($completed / $total) * 100)
                : 0,
        ];
    }

    public function render(): View
    {
        return view('livewire.release-view');
    }
}
