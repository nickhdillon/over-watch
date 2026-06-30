<flux:navbar class="px-4 overflow-x-auto scrollbar-none">
    @if ($release)
        <flux:navbar.item
            :href="route('project.release.view', [$project, $release])"
            :current="request()->routeIs('project.release.view')"
            wire:navigate
        >
            Overview
        </flux:navbar.item>

        <flux:navbar.item
            :href="$project
                ? route('project.release.edit', [$project, $release])
                : route('release.edit', $release)"
            :current="request()->routeIs('project.release.edit', 'release.edit')"
            wire:navigate
        >
            Settings
        </flux:navbar.item>
    @elseif ($project)
        <flux:navbar.item
            :href="route('project.view', $project)"
            :current="request()->routeIs('project.view')"
            wire:navigate
        >
            Overview
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('project.releases', $project)"
            :current="request()->routeIs('project.release*')"
            wire:navigate
        >
            Releases
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('project.tickets', $project)"
            :current="request()->routeIs('project.tickets')"
            wire:navigate
        >
            Tickets
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('project.tags', $project)"
            :current="request()->routeIs('project.tags')"
            wire:navigate
        >
            Tags
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
            :href="route('releases')"
            :current="request()->routeIs('release*')"
            wire:navigate
        >
            Releases
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('tickets')"
            :current="request()->routeIs('tickets')"
            wire:navigate
        >
            Tickets
        </flux:navbar.item>

        <flux:navbar.item
            :href="route('profile.edit')"
            :current="request()->is('settings*')"
            wire:navigate
        >
            Settings
        </flux:navbar.item>
    @endif
</flux:navbar>