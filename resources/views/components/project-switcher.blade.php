<div
    x-data="{ menuOpen: false }"
    x-on:click.outside="menuOpen = false"
    x-on:click.away="menuOpen = false"
    x-on:keydown.escape="menuOpen = false"
    class="flex items-center"
>
    <div class="text-neutral-300 dark:text-neutral-500 pr-2 hidden sm:flex">/</div>

    @if ($current_project)
        <div>
            <a
                href="{{ route('project.view', $current_project) }}"
                wire:navigate.hover
                class="group flex items-center hover:bg-neutral-100 dark:hover:bg-neutral-700 gap-2 h-7 pl-0 sm:p-1! pr-1.5 rounded-md w-full text-left text-sm"
            >
                @if (! $current_project->image_path)
                    <div class="flex relative size-5 items-center justify-center rounded-sm bg-neutral-400 dark:bg-neutral-600 border border-neutral-50 dark:border-white/10">
                        <span class="text-xs font-medium text-white">
                            {{ Str::of($current_project?->name)->substr(0, 1)->upper() }}
                        </span>
    
                        @if ($current_project->color) 
                            <div class="absolute bottom-[-4.2px] right-[-4.2px] p-[2.1px] bg-white group-hover:bg-neutral-100 dark:bg-neutral-900 dark:group-hover:bg-neutral-700 rounded-sm">
                                <div class="{{ $current_project->color->background() }} size-1.5 sm:size-1.75 rounded-xs"></div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="flex relative size-5 items-center justify-center rounded-sm border border-neutral-200 dark:border-white/10">
                        <img
                            src="{{ Storage::disk('s3')->url($current_project->image_path) }}"
                            alt="{{ $current_project->name }}"
                            class="rounded-sm"
                        />
    
                        @if ($current_project->color) 
                            <div class="absolute bottom-[-4.2px] right-[-4.2px] p-[2.1px] bg-white group-hover:bg-neutral-100 dark:bg-neutral-900 dark:group-hover:bg-neutral-700 rounded-sm">
                                <div class="{{ $current_project->color->background() }} size-1.5 sm:size-1.75 rounded-xs"></div>
                            </div>
                        @endif
                    </div>
                @endif

                <span class="min-w-0 max-w-[45vw] truncate text-sm font-medium text-neutral-700 dark:text-white">
                    {{ $current_project?->name }}
                </span>
            </a>
        </div>
    @endif

    <div>
        <button
            type="button"
            x-ref="button"
            x-on:click="menuOpen = ! menuOpen"
            class="flex h-7 w-6 items-center justify-center rounded-md hover:bg-neutral-100 dark:hover:bg-neutral-700"
        >
            <svg
                viewBox="0 0 16 16"
                fill="none"
                class="size-4"
                aria-hidden="true"
            >
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
                class="mt-1 z-50 w-50 rounded-lg border border-neutral-300 bg-white shadow-md dark:border-neutral-700/50 dark:bg-neutral-800 [&>a]:w-full [&>a]:p-2.5"
            >
                <div class="p-1">
                    @foreach (auth()->user()->projects()->orderBy('name')->get() as $project)
                        <a
                            href="{{ route($target_route, $project) }}"
                            wire:navigate.hover
                            class="group min-w-0 flex items-center gap-2 hover:bg-neutral-100 dark:hover:bg-neutral-700 p-2! rounded w-full text-left text-sm"
                        >
                            @if (! $project->image_path)
                                <div class="flex relative size-5 items-center justify-center rounded-sm bg-neutral-400 dark:bg-neutral-600 border border-neutral-200 dark:border-white/10">
                                    <span class="text-xs font-medium text-white">
                                        {{ Str::of($project->name)->substr(0, 1)->upper() }}
                                    </span>

                                    @if ($project->color) 
                                        <div class="absolute -bottom-1 -right-1 p-0.5 bg-white group-hover:bg-neutral-100 dark:bg-neutral-800 dark:group-hover:bg-neutral-700 rounded-sm">
                                            <div class="{{ $project->color->background() }} size-1.5 sm:size-1.75 rounded-xs"></div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="flex relative size-5 items-center justify-center rounded-sm border border-neutral-200 dark:border-white/10">
                                    <img
                                        src="{{ Storage::disk('s3')->url($project->image_path) }}"
                                        alt="{{ $project->name }}"
                                        class="rounded-sm"
                                    />

                                    @if ($project->color) 
                                        <div class="absolute -bottom-1 -right-1 p-0.5 bg-white group-hover:bg-neutral-100 dark:bg-neutral-800 dark:group-hover:bg-neutral-700 rounded-sm">
                                            <div class="{{ $project->color->background() }} size-1.5 sm:size-1.75 rounded-xs"></div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <span class="min-w-0 truncate font-medium">{{ $project->name }}</span>
                        </a>
                    @endforeach
                </div>

                <flux:separator class="bg-neutral-300! dark:bg-neutral-700/50!" />

                <div class="p-1">
                    <flux:modal.trigger name="new-project" x-on:click.stop>
                        <button type="button" class="hover:bg-[#18b69b]/10 flex items-center gap-2 p-2 rounded w-full text-left text-sm">
                            <div class="size-5 flex items-center justify-center">
                                <div class="border border-neutral-300 dark:border-neutral-700 rounded-sm bg-white dark:bg-neutral-800">
                                    <flux:icon name="plus" class="size-4 p-px text-[#18b69b]" />
                                </div>
                            </div>

                            <span class="text-[#18b69b] font-medium">New project</span>
                        </button>
                    </flux:modal.trigger>
                </div>
            </div>
        </template>
    </div>

    <livewire:project-form-modal />
</div>
