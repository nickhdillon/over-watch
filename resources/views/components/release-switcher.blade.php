@if ($current_project && $current_release)
    <div
        x-data="{ menuOpen: false }"
        x-on:click.outside="menuOpen = false"
        x-on:keydown.escape="menuOpen = false"
        class="flex items-center"
    >
        <div class="px-1.5 text-neutral-300 dark:text-neutral-500">/</div>

        <a
            href="{{ route('project.release.view', [$current_project, $current_release]) }}"
            wire:navigate.hover
            class="group flex h-7 items-center rounded-md py-1 px-1.5 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-700"
        >
            <span class="block sm:hidden w-full text-sm font-medium text-neutral-700 dark:text-white">
                {{ Str::limit($current_release->name, 12, '...') }}
            </span>

            <span class="hidden sm:block md:hidden w-full text-sm font-medium text-neutral-700 dark:text-white">
                {{ Str::limit($current_release->name, 28, '...') }}
            </span>

            <span class="hidden md:block w-full text-sm font-medium text-neutral-700 dark:text-white">
                {{ $current_release->name }}
            </span>
        </a>

        <button
            type="button"
            x-ref="button"
            x-on:click="menuOpen = ! menuOpen"
            class="flex h-7 w-6 items-center justify-center rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-700"
        >
            <svg viewBox="0 0 16 16" fill="none" class="size-4" aria-hidden="true">
                <path
                    d="M5.33301 6L7.99967 3.33334L10.6663 6M10.6663 10L7.99967 12.6667L5.33301 10"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="stroke-neutral-500 dark:stroke-neutral-400"
                />
            </svg>
        </button>

        <template x-teleport="body">
            <div
                x-cloak
                x-show="menuOpen"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 -translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-1"
                x-anchor.bottom-start="$refs.button"
                class="z-50 mt-1 w-50 rounded-lg border border-neutral-300 bg-white shadow-md dark:border-neutral-700/50 dark:bg-neutral-800"
            >
                <div class="p-1">
                    @foreach ($current_project->releases()->orderBy('name')->get() as $release)
                        <a
                            href="{{ route($release_route, [$current_project, $release]) }}"
                            wire:navigate.hover
                            class="group flex w-full min-w-0 items-center rounded px-2 py-1.5 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-700"
                        >
                            <span class="min-w-0 truncate font-medium">
                                {{ $release->name }}
                            </span>
                        </a>
                    @endforeach
                </div>

                <flux:separator class="bg-neutral-300! dark:bg-neutral-700/50!" />

                <div class="p-1">
                    <flux:modal.trigger name="new-release" x-on:click.stop>
                        <button type="button" class="flex w-full items-center gap-2 rounded px-2 py-1.5 text-left text-sm hover:bg-violet-50 dark:hover:bg-violet-500/10">
                            <div class="flex size-5 items-center justify-center">
                                <div class="rounded-sm border border-neutral-300 bg-white dark:border-neutral-700 dark:bg-neutral-800">
                                    <flux:icon name="plus" class="size-4 p-px text-violet-600 dark:text-violet-400" />
                                </div>
                            </div>

                            <span class="font-medium text-violet-600 dark:text-violet-400">New release</span>
                        </button>
                    </flux:modal.trigger>
                </div>
            </div>
        </template>

        <livewire:release-form-modal :project="$current_project" />
    </div>
@endif
