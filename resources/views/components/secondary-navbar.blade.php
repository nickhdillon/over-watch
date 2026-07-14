<flux:navbar class="px-4 overflow-x-auto scrollbar-none">
    @if ($release)
        <flux:navbar.item
            :href="route('project.release.view', [$project, $release])"
            :current="request()->routeIs('project.release.view')"
            wire:navigate.hover
        >
            Overview
        </flux:navbar.item>

        <flux:navbar.item
            :href="$project
                ? route('project.release.edit', [$project, $release])
                : route('release.edit', $release)"
            :current="request()->routeIs('project.release.edit', 'release.edit')"
            wire:navigate.hover
        >
            Settings
        </flux:navbar.item>
    @elseif ($project)
        <flux:navbar.item
            :href="route('project.view', $project)"
            :current="request()->routeIs('project.view')"
            wire:navigate.hover
        >
            Overview
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('project.releases', $project)"
            :current="request()->routeIs('project.release*')"
            wire:navigate.hover
        >
            Releases
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('project.tickets', $project)"
            :current="request()->routeIs('project.tickets')"
            wire:navigate.hover
        >
            Tickets
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('project.tags', $project)"
            :current="request()->routeIs('project.tags')"
            wire:navigate.hover
        >
            Tags
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('project.edit', $project)"
            :current="request()->routeIs('project.edit')"
            wire:navigate.hover
        >
            Settings
        </flux:navbar.item>
    @else
        <flux:navbar.item
            :href="route('dashboard')"
            :current="request()->routeIs('dashboard')"
            wire:navigate.hover
        >
            Overview
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('projects')"
            :current="request()->routeIs('projects')"
            wire:navigate.hover
        >
            Projects
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('releases')"
            :current="request()->routeIs('release*')"
            wire:navigate.hover
        >
            Releases
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('tickets')"
            :current="request()->routeIs('tickets')"
            wire:navigate.hover
        >
            Tickets
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('profile.edit')"
            :current="request()->is('settings*')"
            wire:navigate.hover
        >
            Settings
        </flux:navbar.item>
    @endif
</flux:navbar>