<div>
    <flux:navbar class="px-4">
        <flux:navbar.item :href="route('project.view', $project)" :current="request()->routeIs('project.view')">
            Overview
        </flux:navbar.item>

        <flux:navbar.item :href="route('project.tickets', $project)" :current="request()->routeIs('project.tickets')">
            Tickets
        </flux:navbar.item>

        <flux:navbar.item :href="route('project.edit', $project)" :current="request()->routeIs('project.edit')">
            Settings
        </flux:navbar.item>
    </flux:navbar>

    <div class="border-t sm:border border-neutral-200 dark:border-neutral-700 shadow-xs bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">    
        <div class="flex items-center justify-between border-b gap-2 border-neutral-200 dark:border-neutral-700 py-3 px-4 sm:py-5 sm:px-6">
            <div class="flex items-center gap-3 first:rounded-t-xl last:rounded-b-xl">
                @if (! $project->image_path)
                    <div class="flex relative size-10 sm:size-12 items-center justify-center rounded-md bg-neutral-400 dark:bg-neutral-600 border border-neutral-200 dark:border-white/10">
                        <span class="text-lg font-medium text-white">
                            {{ Str::of($project->name)->substr(0, 1)->upper() }}
                        </span>

                        @if ($project->color) 
                            <div class="absolute -bottom-1.75 -right-1.75 p-0.75 sm:p-[3.5px] bg-white dark:bg-[#1A1A1A] rounded-md">
                                <div class="bg-{{ $project->color }}-500 size-3 sm:size-3.5 rounded-[3px]"></div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="flex relative size-10 sm:size-12 items-center justify-center rounded-md border border-neutral-200 dark:border-white/10">
                        <img
                            src="{{ Storage::disk('s3')->url($project->image_path) }}"
                            alt="{{ $project->name }}"
                            class="rounded-md"
                        />

                        @if ($project->color) 
                            <div class="absolute -bottom-1.75 -right-1.75 p-0.75 sm:p-[3.5px] bg-white dark:bg-[#1A1A1A] rounded-md">
                                <div class="bg-{{ $project->color }}-500 size-3 sm:size-3.5 rounded-[3px]"></div>
                            </div>
                        @endif
                    </div>
                @endif

                <span class="text-sm font-medium text-neutral-700 dark:text-white">
                    {{ $project->name }}
                </span>
            </div>

            @if ($project->url) 
                <flux:button :href="$project->url" target="_blank" size="sm">
                    <flux:icon.globe-alt class="size-5 -mr-0.5" />
                    
                    <span>Visit</span>
                </flux:button>
            @endif
        </div>
    </div>
</div>
