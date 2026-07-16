<div
    x-data="selectableList()"
    class="border-t sm:border border-neutral-200 space-y-3 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2"
>
    <div class="p-4 sm:py-12 mx-auto sm:w-11/12 max-w-360">
        <div class="mb-3 flex items-center justify-between gap-4">
            <div class="flex items-center justify-between gap-4 sm:justify-start">
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

                <nav class="-my-px flex h-8.5 rounded-lg bg-neutral-800/5 p-0.75 dark:bg-white/10" aria-label="Ticket view">
                    <a
                        href="{{ route($project ? 'project.tickets' : 'tickets', ['project' => $project, 'view' => 'board']) }}"
                        wire:navigate.hover
                        @class([
                            'flex flex-1 items-center justify-center whitespace-nowrap rounded-md px-3 text-sm font-medium',
                            'text-neutral-600 hover:text-neutral-800 dark:text-white/70 dark:hover:text-white',
                            'bg-white text-neutral-800 shadow-xs dark:bg-white/20 dark:text-white!' => $view === 'board',
                        ])
                        @if ($view === 'board') aria-current="page" @endif
                    >
                        Board
                    </a>

                    <a
                        href="{{ route($project ? 'project.tickets' : 'tickets', ['project' => $project, 'view' => 'list']) }}"
                        wire:navigate.hover
                        @class([
                            'flex flex-1 items-center justify-center whitespace-nowrap rounded-md px-3 text-sm font-medium',
                            'text-neutral-600 hover:text-neutral-800 dark:text-white/70 dark:hover:text-white',
                            'bg-white text-neutral-800 shadow-xs dark:bg-white/20 dark:text-white!' => $view === 'list',
                        ])
                        @if ($view === 'list') aria-current="page" @endif
                    >
                        List
                    </a>
                </nav>
            </div>

            <flux:modal.trigger x-on:click="$flux.modal('ticket-form').show()">
                <flux:button variant="primary" icon="plus" size="sm">
                    New ticket
                </flux:button>
            </flux:modal.trigger>
        </div>

        @if ($view === 'board')
            <div class="mb-3">
                <x-ticket-list-controls :$filters />
            </div>

            @island(name: 'board-tickets', defer: true, always: true)
                @use('App\Enums\Status', 'Status')

                @placeholder
                    <div class="overflow-x-auto overflow-y-visible pb-4 md:-mx-6 md:px-6 md:pb-6">
                        <flux:skeleton.group animate="shimmer" class="flex items-stretch gap-4 pr-4 md:pr-0">
                            @foreach (range(1, 4) as $column)
                                <div class="flex w-80 max-w-80 shrink-0 flex-col rounded-xl border border-neutral-200 bg-neutral-400/5 shadow-xs dark:border-neutral-800 dark:bg-neutral-900">
                                    <div class="flex items-center gap-2 px-4 py-3">
                                        <flux:skeleton.line class="w-24" />
                                        <flux:skeleton class="size-5 rounded-sm" />
                                    </div>

                                    <div class="flex flex-1 flex-col gap-2 px-2 pb-2">
                                        @foreach (range(1, $column % 2 === 0 ? 2 : 3) as $card)
                                            <div class="space-y-3 rounded-lg border border-neutral-300/60 bg-white/50 p-3 shadow-xs dark:border-neutral-700/40 dark:bg-neutral-800/50">
                                                <div class="space-y-1">
                                                    <flux:skeleton.line class="w-16" />
                                                    <flux:skeleton.line @class(['w-4/5' => $card % 2, 'w-2/3' => ! ($card % 2)]) />
                                                </div>

                                                <div class="flex items-center justify-between gap-3">
                                                    <flux:skeleton class="h-5 w-16 rounded-sm" />

                                                    <div class="flex items-center gap-1">
                                                        <flux:skeleton class="size-5 rounded-sm" />
                                                        <flux:skeleton class="size-7 rounded-full" />
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </flux:skeleton.group>
                    </div>
                @endplaceholder

                <div class="overflow-x-auto overflow-y-visible pb-4 md:-mx-6 md:px-6 md:pb-6">
                    <div
                        wire:sortable-group="updateTicketGroupOrder"
                        wire:sortable.options="{ animation: 100 }"
                        class="flex items-stretch gap-3 pr-4 md:pr-0"
                    >
                        @foreach (Status::cases() as $status)
                            @php
                                $status_tickets = $this->ticketsByStatus->get($status->value, collect());
                            @endphp

                            <div wire:key="status-{{ $status->value }}" class="flex">
                                <div class="flex flex-col rounded-xl w-80 max-w-80 bg-neutral-400/5 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 shadow-xs">
                                    <div class="py-3 px-4 flex justify-between items-start">
                                        <div class="flex items-center gap-1.5">
                                            <flux:heading class="mb-0!">{{ $status->label() }}</flux:heading>

                                            <flux:subheading class="bg-neutral-200 dark:bg-neutral-700 rounded-sm px-1.75 py-px text-xs">
                                                {{ $status_tickets->count() }}
                                            </flux:subheading>
                                        </div>
                                    </div>

                                    <div
                                        wire:sortable-group.item-group="{{ $status->value }}"
                                        wire:sortable-group.options="{ animation: 100, ghostClass: 'sortable-ghost-ticket' }"
                                        class="flex flex-1 flex-col gap-2 px-2 pb-2 min-h-24"
                                    >
                                        @foreach ($status_tickets as $ticket)
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
                                                        <div class="flex items-center gap-1.5">
                                                            <p class="text-xs font-medium text-neutral-500 dark:text-neutral-400">
                                                                {{ $ticket->issue_key }}
                                                            </p>

                                                            @if ($ticket->release)
                                                                <span class="pointer-events-auto relative z-20">
                                                                    <flux:tooltip :content="$ticket->release->name">
                                                                        <flux:icon.flag
                                                                            variant="solid"
                                                                            class="size-3.5 shrink-0 text-violet-400 dark:text-violet-500"
                                                                        />
                                                                    </flux:tooltip>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        
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
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endisland
        @else
            <div class="overflow-hidden rounded-xl border border-neutral-300 bg-white/30 shadow-xs dark:border-neutral-700 dark:bg-neutral-900/20">
                <x-ticket-list-controls :$filters integrated />

                @island(defer: true, always: true)
                    @placeholder
                        <flux:skeleton.group
                            animate="shimmer"
                            class="divide-y divide-neutral-200 dark:divide-neutral-700"
                        >
                            @foreach (range(1, 8) as $row)
                                <div class="flex min-w-0 items-center justify-between gap-3 px-3.5 py-3">
                                    <div class="flex min-w-0 flex-1 items-center gap-2">
                                        <flux:skeleton.line class="w-14 shrink-0" />
                                        <flux:skeleton.line @class(['max-w-md', 'w-2/3' => $row % 3 === 0, 'w-1/2' => $row % 3 === 1, 'w-3/4' => $row % 3 === 2]) />
                                    </div>

                                    <div class="flex shrink-0 items-center gap-2.5 sm:gap-6">
                                        <flux:skeleton class="hidden h-5 w-16 rounded-sm sm:block" />
                                        <flux:skeleton class="size-5 rounded-sm" />
                                        <flux:skeleton class="size-7 rounded-full" />
                                    </div>
                                </div>
                            @endforeach
                        </flux:skeleton.group>
                    @endplaceholder

                    <div
                        wire:sortable="updateTicketOrder"
                        wire:sortable.options="{ animation: 100 }"
                        class="divide-y divide-neutral-200 bg-white/50 dark:divide-neutral-700 dark:bg-neutral-800/50"
                    >
                        @forelse ($this->tickets as $ticket)
                            <div
                                class="group relative hover:bg-neutral-50 dark:hover:bg-neutral-800"
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

                                <div class="pointer-events-none flex min-w-0 items-center justify-between gap-3 py-2.5 px-3">
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

                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <p class="text-sm font-medium truncate text-neutral-700 dark:text-white">
                                                {{ $ticket->name }}
                                            </p>

                                            @if ($ticket->release)
                                                <span class="pointer-events-auto relative z-20">
                                                    <flux:tooltip :content="$ticket->release->name">
                                                        <flux:icon.flag
                                                            variant="solid"
                                                            class="size-3.5 shrink-0 text-violet-400 dark:text-violet-500"
                                                        />
                                                    </flux:tooltip>
                                                </span>
                                            @endif
                                        </div>
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

                        @if ($this->tickets->count())
                            <flux:pagination :paginator="$this->tickets" class="px-3! pb-3! border-none!" />
                        @endif
                    </div>
                @endisland
            </div>
        @endif
    </div>

    <x-ticket-bulk-actions-toolbar :releases="$this->releases"/>

    <livewire:ticket-form :$view :project="$project ?? null" />
</div>
