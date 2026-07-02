<div class="border-t sm:border border-neutral-200 space-y-3 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
    <div class="p-4 sm:py-12 mx-auto sm:w-11/12 max-w-5xl">
        <div class="flex items-center justify-between gap-2 mb-4">
            <h1 class="font-medium">Projects</h1>

            <div>
                <flux:modal.trigger name="new-project">
                    <flux:button
                        variant="primary"
                        icon="plus"
                        size="sm"
                    >
                        New project
                    </flux:button>
                </flux:modal.trigger>
                
                <livewire:project-form-modal />
            </div>
        </div>
        
        <div class="border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 shadow-xs">
            @forelse ($this->projects as $project)
                <div
                    wire:key='{{ $project->id }}'
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
                                <div class="flex relative size-8.5 items-center justify-center rounded-sm bg-neutral-400 dark:bg-neutral-600 border border-neutral-200 dark:border-white/10">
                                    <span class="text-md font-medium text-white">
                                        {{ Str::of($project->name)->substr(0, 1)->upper() }}
                                    </span>

                                    @if ($project->color) 
                                        <div class="absolute -bottom-1.75 -right-1.75 p-0.75 sm:p-[3.5px] bg-white group-hover:bg-neutral-50 dark:bg-[#202020] dark:group-hover:bg-neutral-800 rounded-sm">
                                            <div class="{{ $project->color->background() }} size-2 sm:size-2.5 rounded-xs"></div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="flex relative size-8.5 items-center justify-center rounded-sm border border-neutral-200 dark:border-white/10">
                                    <img
                                        src="{{ Storage::disk('s3')->url($project->image_path) }}"
                                        alt="{{ $project->name }}"
                                        class="rounded-sm"
                                    />

                                    @if ($project->color) 
                                        <div class="absolute -bottom-1.75 -right-1.75 p-0.75 sm:p-[3.5px] bg-white group-hover:bg-neutral-50 dark:bg-[#202020] dark:group-hover:bg-neutral-800 rounded-sm">
                                            <div class="{{ $project->color->background() }} size-2 sm:size-2.5 rounded-xs"></div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="flex flex-col -space-y-0.5">
                                <p class="text-sm font-medium text-neutral-700 dark:text-white">
                                    {{ $project->name }}
                                </p>

                                @if ($project->repository_url) 
                                    <a
                                        href="{{ $project->repository_url }}"
                                        target="_blank"
                                        class="text-[13px] text-neutral-500 dark:text-neutral-400"
                                    >
                                        {{ Str::after($project->repository_url, '.com/') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="pointer-events-auto z-20 relative flex items-center gap-2.5">
                            <livewire:priority-switcher :model="$project" />

                            @if ($project->url) 
                                <flux:button
                                    icon="arrow-top-right-on-square"
                                    :href="$project->url"
                                    target="_blank"
                                    size="sm"
                                    variant="ghost"
                                />
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                    No projects
                </div>
            @endforelse
        </div>
    </div>
</div>
