<div>
    <div class="border-t sm:border border-neutral-200 sm:space-y-6 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
        <div class="p-4 sm:pt-12 mx-auto sm:w-11/12">
            <div class="flex items-center justify-between gap-2 mb-4">
                <h1 class="font-medium">Recent projects</h1>

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
                @foreach ($this->recent_projects as $project)
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
                                    <div class="relative flex size-8 items-center justify-center rounded-sm border border-neutral-200 bg-neutral-400 dark:border-white/10 dark:bg-neutral-600">
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
                                    <div class="relative flex size-8 items-center justify-center rounded-sm border border-neutral-200 dark:border-white/10">
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

                                <div class="flex flex-col -space-y-0.5">
                                    <p class="text-sm font-medium text-neutral-700 dark:text-white">
                                        {{ $project->name }}
                                    </p>

                                    @if ($project->repository_url) 
                                        <a
                                            href="{{ $project->repository_url }}"
                                            target="_blank"
                                            class="text-[13px] text-neutral-500"
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
                @endforeach
            </div>
        </div>

        <div class="p-4 mx-auto sm:w-11/12">
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

            <livewire:ticket-form />
        </div>
    </div>
</div>
