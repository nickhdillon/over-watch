<div>
    <flux:navbar class="px-4">
        @if ($project) 
            <flux:navbar.item :href="route('project.view', $project)" :current="request()->routeIs('project.view')">
                Overview
            </flux:navbar.item>
    
            <flux:navbar.item :href="route('project.tickets', $project)" :current="request()->routeIs('project.tickets')">
                Tickets
            </flux:navbar.item>
    
            <flux:navbar.item :href="route('project.edit', $project)" :current="request()->routeIs('project.edit')">
                Settings
            </flux:navbar.item>
        @else
            <flux:navbar.item href="/" :current="request()->is('/')">
                Overview
            </flux:navbar.item>

            <flux:navbar.item :href="route('projects')" :current="request()->routeIs('projects')">
                Projects
            </flux:navbar.item>

            <flux:navbar.item :href="route('tickets')" :current="request()->routeIs('tickets')">
                Tickets
            </flux:navbar.item>
        @endif
    </flux:navbar>

    <div class="border-t sm:border border-neutral-200 space-y-3 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
        <div class="p-4 sm:py-12 mx-auto sm:w-11/12">
            <div class="flex items-center justify-between gap-2 mb-4">
                <h1 class="font-medium">Tickets</h1>

                <div>
                    <flux:modal.trigger name="new-ticket">
                        <flux:button icon="plus" size="sm">New ticket</flux:button>
                    </flux:modal.trigger>
                    
                    {{-- <livewire:ticket-form /> --}}
                </div>
            </div>
            
            <div class="border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 shadow-xs">
                @foreach ($this->tickets as $ticket)
                    <a
                        {{-- href="{{ route('ticket.view', $ticket) }}" --}}
                        {{-- wire:navigate --}}
                        class="group flex min-w-0 items-center justify-between gap-3 hover:bg-neutral-50 dark:hover:bg-neutral-800 py-2.5 px-3.5 first:rounded-t-lg last:rounded-b-lg"
                    >
                        <p class="text-sm flex-1 min-w-0 truncate font-medium text-neutral-700 dark:text-white">
                            {{ $ticket->title }}
                        </p>

                        <div class="flex shrink-0 items-center gap-2.5 sm:gap-6">
                            <livewire:status-switcher :$ticket />

                            <livewire:priority-switcher :$ticket />

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
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
