<?php

declare(strict_types=1);

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class ProjectList extends Component
{
    #[Computed]
    public function projects(): Collection
    {
        return auth()
            ->user()
            ->projects()
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.project-list');
    }
}
