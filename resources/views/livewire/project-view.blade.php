<div>
    <div class="border-t sm:border border-neutral-200 dark:border-neutral-700 shadow-xs bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">    
        <div class="flex items-center justify-between border-b gap-2 border-neutral-200 dark:border-neutral-700 p-4">
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

                <div class="flex flex-col gap-1">
                    <p class="text-sm font-medium text-neutral-700 dark:text-white">
                        {{ $project->name }}
                    </p>

                    @if ($project->repository_url) 
                        <a
                            href="{{ $project->repository_url }}"
                            target="_blank"
                            class="text-sm hidden sm:flex items-center gap-1.5 text-neutral-500"
                        >
                            <svg
                                class="size-5 stroke-[1.5px] stroke-neutral-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M16 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                <path d="M12 8m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                <path d="M12 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                <path d="M12 15v-6" />
                                <path d="M15 11l-2 -2" />
                                <path d="M11 7l-1.9 -1.9" />
                                <path d="M13.446 2.6l7.955 7.954a2.045 2.045 0 0 1 0 2.892l-7.955 7.955a2.045 2.045 0 0 1 -2.892 0l-7.955 -7.955a2.045 2.045 0 0 1 0 -2.892l7.955 -7.955a2.045 2.045 0 0 1 2.892 0z" />
                            </svg>

                            <p>{{ Str::after($project->repository_url, '.com/') }}</p>
                        </a>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <livewire:priority-switcher :model="$project" />

                @if ($project->url) 
                    <flux:button :href="$project->url" target="_blank" size="sm" class="ml-1!">
                        <flux:icon.globe-alt class="size-5 -mr-0.5" />
                        
                        <span>Visit</span>
                    </flux:button>
                @endif
            </div>
        </div>

        <div class="p-4">
            <div class="flex items-center justify-between gap-2 mb-4">
                <h1 class="font-medium">Recent tickets</h1>

                <flux:modal.trigger x-on:click="$flux.modal('ticket-form').show()">
                    <flux:button
                        variant="primary"
                        icon="plus"
                        size="sm"
                    >
                        New ticket
                    </flux:button>
                </flux:modal.trigger>
            </div>
            
            <div class="border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 shadow-xs">
                @foreach ($this->recent_tickets as $ticket)
                    <div
                        class="group relative first:rounded-t-lg last:rounded-b-lg hover:bg-neutral-50 dark:hover:bg-neutral-800"
                        wire:key='{{ $ticket->id }}'
                    >
                        <button
                            aria-label="View {{ $ticket->name }}"
                            class="absolute inset-0 z-10 rounded-[inherit]"
                            x-on:click="$dispatch('load-ticket', { ticket_id: {{ $ticket->id }} })"
                        ></button>

                        <div class="pointer-events-none flex min-w-0 items-center justify-between gap-3 py-2.5 px-3.5">
                            <div class="flex flex-1 items-center gap-2 min-w-0 truncate">
                                <p class="text-xs font-medium text-neutral-500 dark:text-neutral-300">
                                    {{ $ticket->issue_key }}
                                </p>

                                <p class="text-sm font-medium truncate text-neutral-700 dark:text-white">
                                    {{ $ticket->name }}
                                </p>
                            </div>

                            <div class="pointer-events-auto z-20 flex shrink-0 items-center gap-2.5 sm:gap-6">
                                <livewire:status-switcher :$ticket />

                                <livewire:priority-switcher :model="$ticket" />

                                @if ($ticket->assignee->avatar)
                                    <flux:avatar
                                        circle
                                        :name="$ticket->assignee->name"
                                        :initials="$ticket->assignee->initials()"
                                        :src="Storage::disk('s3')->url('avatars/' . $ticket->assignee->avatar)"
                                        class="size-7"
                                    />
                                @else
                                    <flux:avatar
                                        circle
                                        :name="$ticket->assignee->name"
                                        :initials="$ticket->assignee->initials()"
                                        class="size-7"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <livewire:ticket-form :$project />
        </div>
    </div>
</div>
