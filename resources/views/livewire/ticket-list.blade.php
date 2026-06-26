<div>
    <x-secondary-navbar />

    <div class="border-t sm:border border-neutral-200 space-y-3 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
        <div class="p-4 sm:py-12 mx-auto sm:w-11/12">
            <div class="flex items-center justify-between gap-2 mb-4">
                <div class="flex items-center gap-4">
                    <h1 class="font-medium">Tickets</h1>

                    <flux:radio.group variant="segmented" wire:model.live="view" size="sm">
                        <flux:radio value="list">List</flux:radio>
                        <flux:radio value="board">Board</flux:radio>
                    </flux:radio.group>
                </div>

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
                @foreach ($this->tickets as $ticket)
                    @if ($view === 'list')
                        <div
                            class="group relative first:rounded-t-lg last:rounded-b-lg hover:bg-neutral-50 dark:hover:bg-neutral-800"
                            wire:key='{{ $ticket->id }}'
                        >
                            <button
                                aria-label="View {{ $ticket->title }}"
                                class="absolute inset-0 z-10 rounded-[inherit]"
                                x-on:click="$dispatch('load-ticket', { ticket_id: {{ $ticket->id }} })"
                            ></button>

                            <div class="pointer-events-none flex min-w-0 items-center justify-between gap-3 py-2.5 px-3.5">
                                <div class="flex flex-1 items-center gap-2 min-w-0 truncate">
                                    <p class="text-xs font-medium text-neutral-500 dark:text-neutral-300">
                                        {{ $ticket->issueKey }}
                                    </p>

                                    <p class="text-sm font-medium truncate text-neutral-700 dark:text-white">
                                        {{ $ticket->title }}
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
                    @else
                        <div
                            class="group relative first:rounded-t-lg last:rounded-b-lg hover:bg-neutral-50 dark:hover:bg-neutral-800"
                            wire:key='{{ $ticket->id }}'
                        >
                            <button
                                aria-label="View {{ $ticket->title }}"
                                class="absolute inset-0 z-10 rounded-[inherit]"
                                x-on:click="$dispatch('load-ticket', { ticket_id: {{ $ticket->id }} })"
                            ></button>

                            <div class="pointer-events-none flex min-w-0 items-center justify-between gap-3 py-2.5 px-3.5">
                                <div class="flex flex-1 items-center gap-2 min-w-0 truncate">
                                    <p class="text-xs font-medium text-neutral-500 dark:text-neutral-300">
                                        {{ $ticket->issueKey }}
                                    </p>

                                    <p class="text-sm font-medium truncate text-neutral-700 dark:text-white">
                                        {{ $ticket->title }}
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

                                    {{-- <flux:dropdown>
                                        <flux:button icon="ellipsis-horizontal" variant="ghost" size="sm" />

                                        <flux:menu>
                                            <flux:modal.trigger x-on:click="$dispatch('load-ticket', { ticket_id: {{ $ticket->id }} })">
                                                <flux:menu.item icon="pencil-square">
                                                    Edit
                                                </flux:menu.item>
                                            </flux:modal.trigger>

                                            <flux:modal.trigger name="delete-ticket{{ $ticket->id }}">
                                                <flux:menu.item variant="danger" icon="trash">
                                                    Delete
                                                </flux:menu.item>
                                            </flux:modal.trigger>

                                            <flux:modal name="delete-ticket{{ $ticket->id }}" class="w-90! sm:w-120!">
                                                <flux:heading size="lg">Delete Ticket</flux:heading>

                                                <flux:text class="mt-2">
                                                    Are you sure you want to delete this ticket?
                                                </flux:text>

                                                <div class="mt-4 flex">
                                                    <flux:spacer />

                                                    <flux:button wire:click="delete({{ $ticket->id }})" variant="danger" size="sm">
                                                        Yes, delete
                                                    </flux:button>
                                                </div>
                                            </flux:modal>
                                        </flux:menu>
                                    </flux:dropdown> --}}
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <livewire:ticket-form />
    </div>
</div>
