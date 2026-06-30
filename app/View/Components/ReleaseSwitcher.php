<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Project;
use App\Models\Release;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ReleaseSwitcher extends Component
{
    public ?Project $current_project = null;

    public ?Release $current_release = null;

    public string $release_route;

    public function __construct()
    {
        $project = request()->route('project');
        $release = request()->route('release');

        $this->current_project = $project instanceof Project ? $project : null;
        $this->current_release = $release instanceof Release ? $release : null;

        $this->release_route = request()->routeIs('project.release.edit')
            ? 'project.release.edit'
            : 'project.release.view';
    }

    public function render(): View
    {
        return view('components.release-switcher');
    }
}
