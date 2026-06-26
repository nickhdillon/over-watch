<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Project;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SecondaryNavbar extends Component
{
    public ?Project $project;

    public function __construct()
    {
        $route_project = request()->route('project');

        $this->project = $route_project instanceof Project
            ? $route_project
            : Project::firstWhere('slug', $route_project);
    }

    public function render(): View
    {
        return view('components.secondary-navbar');
    }
}
