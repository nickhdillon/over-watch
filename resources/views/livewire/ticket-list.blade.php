@use('App\Enums\Status', 'Status')

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
            
            @if ($view === 'list')
                <div class="border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 shadow-xs">
                    @foreach ($this->tickets as $ticket)
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
                    @endforeach
                </div>
            @else
                <div class="overflow-x-auto md:-m-6 md:p-6 pb-4">
                    <div class="flex gap-4 pr-4 md:pr-0">
                        @foreach (Status::cases() as $status)
                            @php
                                $tickets = $this->tickets_by_status->get($status->value, collect());
                            @endphp

                            <div>
                                <div class="rounded-xl w-80 max-w-80 bg-neutral-400/5 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 shadow-xs">
                                    <div class="py-3 px-4 flex justify-between items-start">
                                        <div class="flex items-center gap-1.5">
                                            <flux:heading class="mb-0!">{{ $status->label() }}</flux:heading>

                                            <flux:subheading class="bg-neutral-200 dark:bg-neutral-700 rounded-sm px-1.75 py-px text-xs">
                                                {{ $tickets->count() }}
                                            </flux:subheading>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2 px-2 pb-2">
                                        @forelse ($tickets as $ticket)
                                            <div
                                                class="group relative rounded-lg border border-neutral-300/60 dark:border-neutral-700/40 bg-white/50 dark:bg-neutral-800/50 p-3 shadow-xs hover:bg-neutral-100/5 dark:hover:bg-neutral-800"
                                                wire:key="board-ticket-{{ $ticket->id }}"
                                            >
                                                <button
                                                    aria-label="View {{ $ticket->title }}"
                                                    class="absolute inset-0 z-10 rounded-[inherit]"
                                                    x-on:click="$dispatch('load-ticket', { ticket_id: {{ $ticket->id }} })"
                                                ></button>

                                                <div class="pointer-events-none space-y-3">
                                                    <div>
                                                        <p class="text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                                            {{ $ticket->issueKey }}
                                                        </p>
                                                        
                                                        <p class="text-sm font-medium text-neutral-700 dark:text-white">
                                                            {{ $ticket->title }}
                                                        </p>
                                                    </div>

                                                    <div class="pointer-events-auto relative z-20 flex justify-end items-center gap-1">
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
                                        @empty
                                            <div class="rounded-lg border border-dashed border-neutral-300 p-4 text-center text-sm text-neutral-500 dark:border-neutral-700 dark:text-neutral-400">
                                                No tickets
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <livewire:ticket-form />
    </div>
</div>
