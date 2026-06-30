<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Project;
use App\Models\Release;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SecondaryNavbar extends Component
{
    public ?Project $project = null;

    public ?Release $release = null;

    public function __construct()
    {
        $route_project = request()->route('project');
        $route_release = request()->route('release');

        $this->project = $route_project instanceof Project
            ? $route_project
            : Project::firstWhere('slug', $route_project);

        $this->release = $route_release instanceof Release
            ? $route_release
            : Release::firstWhere('slug', $route_release);

        if (! $this->project && $this->release?->project_id) {
            $this->project = Project::query()->find($this->release->project_id);
        }
    }

    public function render(): View
    {
        return view('components.secondary-navbar');
    }
}
