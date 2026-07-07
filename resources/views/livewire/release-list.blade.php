<div class="border-t sm:border border-neutral-200 space-y-3 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
    <div class="p-4 sm:py-12 mx-auto sm:w-11/12 max-w-5xl">
        <div class="flex items-center justify-between gap-2 mb-4">
            <h1 class="font-medium">Releases</h1>

            <div>
                <flux:modal.trigger name="new-release">
                    <flux:button
                        variant="primary"
                        icon="plus"
                        size="sm"
                    >
                        New release
                    </flux:button>
                </flux:modal.trigger>
                
                <livewire:release-form-modal :$project />
            </div>
        </div>
        
        <div class="border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 shadow-xs">
            @forelse ($this->releases as $release)
                <div
                    wire:key='{{ $release->id }}'
                    class="group relative first:rounded-t-lg last:rounded-b-lg hover:bg-neutral-50 dark:hover:bg-neutral-800"
                >
                    <a
                        href="{{ route('project.release.view', [$project ?? $release->project, $release]) }}"
                        wire:navigate.hover
                        aria-label="View {{ $release->name }}"
                        class="absolute inset-0 z-10 rounded-[inherit]"
                    ></a>

                    <div class="pointer-events-none flex items-center justify-between gap-3 p-3.5">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-neutral-800 dark:text-white">
                                {{ $release->name }}
                            </p>

                            <div class="flex items-center gap-2 text-xs text-neutral-500 dark:text-neutral-400">
                                <span class="truncate">
                                    {{ $release->project->key }}
                                </span>

                                <span aria-hidden="true">&middot;</span>

                                <span>
                                    {{ $release->tickets_count }} {{ Str::plural('ticket', $release->tickets_count) }}
                                </span>
                            </div>
                        </div>

                        <div class="pointer-events-auto z-20 relative flex items-center gap-2.5">
                            <livewire:status-switcher :model="$release" />
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                    No releases
                </div>
            @endforelse
        </div>
    </div>
</div>
