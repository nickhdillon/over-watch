@use('App\Enums\Status', 'Status')

<div x-data="selectableList()">
    <div class="p-4 mx-auto sm:w-11/12 max-w-360">
        <div class="flex items-center justify-between gap-2 mb-4">
            <div class="flex items-center gap-4">
                <div class="group flex items-center">
                    <div
                        x-cloak
                        x-show="$wire.view === 'list'"
                        class="flex w-0 overflow-x-hidden py-px transition-all group-hover:w-6.5"
                        x-bind:class="{ 'w-6.5': selectedTickets.length }"
                    >
                        <flux:checkbox
                            type="checkbox"
                            class="size-4 rounded border-neutral-300"
                            x-ref="selectAllCheckbox"
                            x-bind:checked="selectedTickets.length === {{ $this->tickets->count() }}"
                            x-effect="
                                $refs.selectAllCheckbox.indeterminate =
                                    selectedTickets.length > 0 && selectedTickets.length < {{ $this->tickets->count() }}
                            "
                            x-on:click="
                                selectedTickets.length === {{ $this->tickets->count() }}
                                    ? selectedTickets = []
                                    : selectedTickets = @js($this->tickets->pluck('id')->map(fn ($id) => (string) $id)->values())
                            "
                        />
                    </div>

                    <h1 class="font-medium">Tickets</h1>
                </div>

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
            @php
                $tickets = $this->tickets;
            @endphp

            <div
                wire:sortable="updateTicketOrder"
                wire:sortable.options="{ animation: 100 }"
                class="border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 shadow-xs"
            >
                @forelse ($tickets as $ticket)
                    <div
                        class="group relative first:rounded-t-lg last:rounded-b-lg hover:bg-neutral-50 dark:hover:bg-neutral-800"
                        wire:key="list-ticket-{{ $ticket->id }}"
                        wire:sortable.item="{{ $ticket->id }}"
                        wire:sortable.options="{ animation: 100, ghostClass: 'sortable-ghost-ticket' }"
                        wire:sortable.handle
                    >
                        <button
                            aria-label="View {{ $ticket->name }}"
                            class="absolute inset-0 z-10 rounded-[inherit]"
                            x-on:click="$dispatch('load-ticket', { ticket_id: {{ $ticket->id }} })"
                        ></button>

                        <div class="pointer-events-none flex min-w-0 items-center justify-between gap-3 py-2.5 px-3.5">
                            <div class="flex flex-1 items-center min-w-0 truncate">
                                <div
                                    x-cloak
                                    class="pointer-events-auto z-20 flex py-px w-0 shrink-0 overflow-hidden transition-all group-hover:w-7"
                                    x-bind:class="{ 'w-7': selectedTickets.length || isSelected({{ $ticket->id }}) }"
                                >
                                    <flux:checkbox
                                        x-bind:checked="isSelected({{ $ticket->id }})"
                                        x-on:click.stop="toggleTicket({{ $ticket->id }})"
                                    />
                                </div>

                                <p class="text-xs font-medium pr-2 text-neutral-500 dark:text-neutral-300">
                                    {{ $ticket->issue_key }}
                                </p>

                                <p class="text-sm font-medium truncate text-neutral-700 dark:text-white">
                                    {{ $ticket->name }}
                                </p>
                            </div>

                            <div class="pointer-events-auto z-20 flex shrink-0 items-center gap-2.5 sm:gap-6">
                                <livewire:status-switcher
                                    :model="$ticket"
                                    :wire:key="'list-status-switcher-'.$ticket->id"
                                />

                                <livewire:priority-switcher
                                    :model="$ticket"
                                    :wire:key="'list-priority-switcher-'.$ticket->id"
                                />

                                @if ($ticket->assignee->avatar)
                                    <flux:avatar
                                        circle
                                        :tooltip="$ticket->assignee->name"
                                        :initials="$ticket->assignee->initials()"
                                        :src="Storage::disk('s3')->url('avatars/' . $ticket->assignee->avatar)"
                                        class="size-7 pointer-events-auto z-20"
                                    />
                                @else
                                    <flux:avatar
                                        circle
                                        :tooltip="$ticket->assignee->name"
                                        :initials="$ticket->assignee->initials()"
                                        class="size-7 pointer-events-auto z-20"
                                    />
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                        No tickets
                    </div>
                @endforelse

                @if (count($tickets) > 0) 
                    <flux:pagination :paginator="$this->tickets" class="px-3! pb-3! border-none!" />
                @endif
            </div>
        @else
            <div class="overflow-x-auto md:-m-6 md:p-6 pb-4">
                <div
                    wire:sortable-group="updateTicketGroupOrder"
                    wire:sortable.options="{ animation: 100 }"
                    class="flex gap-4 pr-4 md:pr-0"
                >
                    @foreach (Status::cases() as $status)
                        @php
                            $tickets = $this->tickets_by_status->get($status->value, collect());
                        @endphp

                        <div wire:key="status-{{ $status->value }}">
                            <div class="rounded-xl w-80 max-w-80 bg-neutral-400/5 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 shadow-xs">
                                <div class="py-3 px-4 flex justify-between items-start">
                                    <div class="flex items-center gap-1.5">
                                        <flux:heading class="mb-0!">{{ $status->label() }}</flux:heading>

                                        <flux:subheading class="bg-neutral-200 dark:bg-neutral-700 rounded-sm px-1.75 py-px text-xs">
                                            {{ $tickets->count() }}
                                        </flux:subheading>
                                    </div>
                                </div>

                                <div
                                    wire:sortable-group.item-group="{{ $status->value }}"
                                    wire:sortable-group.options="{ animation: 100, ghostClass: 'sortable-ghost-ticket' }"
                                    class="flex flex-col gap-2 px-2 pb-2"
                                >
                                    @forelse ($tickets as $ticket)
                                        <div
                                            wire:key="board-ticket-{{ $ticket->id }}"
                                            wire:sortable-group.item="{{ $ticket->id }}"
                                            wire:sortable-group.handle
                                            class="group relative rounded-lg border border-neutral-300/60 dark:border-neutral-700/40 bg-white/50 dark:bg-neutral-800/50 p-3 shadow-xs hover:bg-neutral-100/5 dark:hover:bg-neutral-800"
                                        >
                                            <button
                                                aria-label="View {{ $ticket->name }}"
                                                class="absolute inset-0 z-10 rounded-[inherit]"
                                                x-on:click="$dispatch('load-ticket', { ticket_id: {{ $ticket->id }} })"
                                            ></button>

                                            <div class="pointer-events-none space-y-3">
                                                <div>
                                                    <p class="text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                                        {{ $ticket->issue_key }}
                                                    </p>
                                                    
                                                    <p class="text-sm font-medium text-neutral-700 dark:text-white">
                                                        {{ $ticket->name }}
                                                    </p>
                                                </div>

                                                <div class="relative z-20 flex items-center gap-1">
                                                    @if ($ticket->tags->isNotEmpty())
                                                        <div class="pointer-events-none flex items-center flex-wrap gap-1.5">
                                                            @foreach ($ticket->tags->take(1) as $tag)
                                                                <flux:badge size="sm" :color="$tag->color->value">
                                                                    {{ $tag->name }}
                                                                </flux:badge>
                                                            @endforeach

                                                            @if ($ticket->tags->count() > 1)
                                                                <p class="text-xs font-medium text-neutral-500 dark:text-neutral-300">
                                                                    +{{ $ticket->tags->count() - 1 }}
                                                                </p>
                                                            @endif
                                                        </div>
                                                    @endif
                                                
                                                    <div class="pointer-events-auto flex ml-auto justify-end items-center gap-1">
                                                        <livewire:priority-switcher :model="$ticket" />

                                                        @if ($ticket->assignee->avatar)
                                                            <flux:avatar
                                                                circle
                                                                :tooltip="$ticket->assignee->name"
                                                                :initials="$ticket->assignee->initials()"
                                                                :src="Storage::disk('s3')->url('avatars/' . $ticket->assignee->avatar)"
                                                                class="size-7 pointer-events-auto z-20"
                                                            />
                                                        @else
                                                            <flux:avatar
                                                                circle
                                                                :tooltip="$ticket->assignee->name"
                                                                :initials="$ticket->assignee->initials()"
                                                                class="size-7 pointer-events-auto z-20"
                                                            />
                                                        @endif
                                                    </div>
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

    <x-ticket-bulk-actions-toolbar :releases="$this->releases"/>

    <livewire:ticket-form :$view :project="$release->project" :$release />
</div>
