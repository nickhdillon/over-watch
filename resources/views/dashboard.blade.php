<x-layouts::app>
    <flux:navbar class="px-4">
        <flux:navbar.item href="/" :current="request()->is('/')">
            Overview
        </flux:navbar.item>

        <flux:navbar.item :href="route('projects')" :current="request()->routeIs('projects')">
            Projects
        </flux:navbar.item>

        <flux:navbar.item :href="route('tickets')" :current="request()->routeIs('tickets')">
            Tickets
        </flux:navbar.item>
    </flux:navbar>

    <div class="border-t sm:border border-neutral-200 space-y-3 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
        <div class="p-4 sm:py-12 mx-auto sm:w-11/12">
            <div class="flex items-center justify-between gap-2 mb-4">
                <h1 class="font-medium">Recent projects</h1>

                <div>
                    <flux:modal.trigger name="new-project">
                        <flux:button icon="plus" size="sm">New project</flux:button>
                    </flux:modal.trigger>
                    
                    <livewire:project-form-modal />
                </div>
            </div>
            
            <div class="border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 shadow-xs">
                @foreach ($recent_projects as $project)
                    <div
                        wire:key="{{ $project->id }}"
                        class="group relative first:rounded-t-lg last:rounded-b-lg hover:bg-neutral-50 dark:hover:bg-neutral-800"
                    >
                        <a
                            href="{{ route('project.view', $project) }}"
                            wire:navigate
                            aria-label="View {{ $project->name }}"
                            class="absolute inset-0 z-10 rounded-[inherit]"
                        ></a>

                        <div class="pointer-events-none flex items-center justify-between gap-3 p-3.5">
                            <div class="flex items-center gap-3">
                                @if (! $project->image_path)
                                    <div class="relative flex size-7 sm:size-8 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-400 dark:border-white/10 dark:bg-neutral-600">
                                        <span class="text-md font-medium text-white">
                                            {{ Str::of($project->name)->substr(0, 1)->upper() }}
                                        </span>

                                        @if ($project->color)
                                            <div class="absolute -right-1.75 -bottom-1.75 rounded-sm bg-white p-0.75 group-hover:bg-neutral-50 dark:bg-[#202020] dark:group-hover:bg-neutral-800 sm:p-[3.5px]">
                                                <div class="size-2 rounded-xs bg-{{ $project->color }}-500 sm:size-2.5"></div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="relative flex size-7 sm:size-8 items-center justify-center rounded-sm border border-neutral-200 dark:border-white/10">
                                        <img
                                            src="{{ Storage::disk('s3')->url($project->image_path) }}"
                                            alt="{{ $project->name }}"
                                            class="rounded-sm"
                                        />

                                        @if ($project->color)
                                            <div class="absolute -right-1.75 -bottom-1.75 rounded-sm bg-white p-0.75 group-hover:bg-neutral-50 dark:bg-[#202020] dark:group-hover:bg-neutral-800 sm:p-[3.5px]">
                                                <div class="size-2 rounded-xs bg-{{ $project->color }}-500 sm:size-2.5"></div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <span class="text-sm font-medium text-neutral-700 dark:text-white">
                                    {{ $project->name }}
                                </span>
                            </div>

                            <div class="pointer-events-auto z-20 relative flex items-center gap-3">
                                <livewire:priority-switcher :model="$project" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::app>
