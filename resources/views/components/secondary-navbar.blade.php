<flux:navbar class="px-4">
    @if ($project)
        <flux:navbar.item
            :href="route('project.view', $project)"
            :current="request()->routeIs('project.view')"
            wire:navigate
        >
            Overview
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('project.tickets', $project)"
            :current="request()->routeIs('project.tickets')"
            wire:navigate
        >
            Tickets
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('project.edit', $project)"
            :current="request()->routeIs('project.edit')"
            wire:navigate
        >
            Settings
        </flux:navbar.item>
    @else
        <flux:navbar.item
            href="/"
            :current="request()->is('/')"
            wire:navigate
        >
            Overview
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('projects')"
            :current="request()->routeIs('projects')"
            wire:navigate
        >
            Projects
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('tickets')"
            :current="request()->routeIs('tickets')"
            wire:navigate
        >
            Tickets
        </flux:navbar.item>
    @endif
</flux:navbar>