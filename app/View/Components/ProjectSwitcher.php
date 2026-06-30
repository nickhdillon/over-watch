<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Project;
use App\Models\Release;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ProjectSwitcher extends Component
{
    public ?Project $current_project = null;

    public ?Release $current_release = null;

    public string $target_route;

    public function __construct()
    {
        $route_project = request()->route('project');
        $route_release = request()->route('release');

        $this->current_project = $route_project instanceof Project
            ? $route_project
            : Project::query()
                ->where('slug', $route_project)
                ->first();

        $this->current_release = $route_release instanceof Release
            ? $route_release
            : Release::query()
                ->with('project')
                ->where('slug', $route_release)
                ->first();

        if (! $this->current_project && $this->current_release) {
            /** @var Project|null $project */
            $project = $this->current_release->project;

            $this->current_project = $project;
        }

        $route_name = request()->route()?->getName();

        $project_route_map = [
            'project.view' => 'project.view',
            'project.edit' => 'project.edit',
            'project.releases' => 'project.releases',
            'project.release.view' => 'project.releases',
            'project.release.edit' => 'project.releases',

            'releases' => 'project.releases',

            'tickets' => 'project.tickets',
            'project.tickets' => 'project.tickets',
            'project.tags' => 'project.tags',
        ];

        $this->target_route = $project_route_map[$route_name] ?? 'project.view';
    }

    public function render(): View
    {
        return view('components.project-switcher');
    }
}
