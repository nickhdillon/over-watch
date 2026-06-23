<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\RecentView;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $recent_projects = RecentView::query()
            ->where('user_id', auth()->id())
            ->whereHasMorph('viewable', [Project::class])
            ->with('viewable')
            ->latest('last_viewed_at')
            ->limit(5)
            ->get()
            ->pluck('viewable');

        return view('dashboard', [
            'recent_projects' => $recent_projects
        ]);
    }
}
