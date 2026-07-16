@props([
    'filters' => [],
    'integrated' => false,
])

@use('App\Enums\Priority', 'Priority')
@use('App\Enums\Status', 'Status')

<div
    {{-- x-data="{
        sort: 'manual',
        sortLabels: {
            manual: 'Manual order',
            updated: 'Recently updated',
            created: 'Recently created',
            priority: 'Priority',
            due_date: 'Due date',
        },
        sortTriggerLabels: {
            updated: 'Updated',
            created: 'Created',
            priority: 'Priority',
            due_date: 'Due date',
        },
    }" --}}
    @class([
        'p-2',
        'bg-white/30 dark:bg-neutral-800/50 border-b border-neutral-200 dark:border-neutral-700' => $integrated,
        'rounded-xl bg-neutral-400/5 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-800 shadow-xs' => ! $integrated,
    ])
>
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
        <flux:input
            icon="magnifying-glass"
            placeholder="Search tickets"
            class="w-full sm:w-64! [&>input]:h-8.5!"
            wire:model.live.debounce.250ms="search"
            clearable
        />

        <div class="flex items-center gap-2 sm:ml-auto">
            {{-- <flux:dropdown position="bottom" align="end">
                <div>
                    <div x-show="sort === 'manual'">
                        <flux:button
                            type="button"
                            icon="arrows-up-down"
                            size="sm"
                            x-bind:title="`Sort: ${sortLabels[sort]}`"
                            x-bind:aria-label="`Sort tickets by ${sortLabels[sort]}`"
                        >
                            Sort
                        </flux:button>
                    </div>

                    <div x-cloak x-show="sort !== 'manual'">
                        <flux:button
                            type="button"
                            icon="arrows-up-down"
                            size="sm"
                            x-bind:title="`Sort: ${sortLabels[sort]}`"
                            x-bind:aria-label="`Sort tickets by ${sortLabels[sort]}`"
                        >
                            <span x-text="sortTriggerLabels[sort]"></span>
                        </flux:button>
                    </div>
                </div>

                <flux:menu class="min-w-48">
                    <flux:menu.heading>Sort by</flux:menu.heading>

                    <flux:menu.radio.group x-model="sort">
                        <flux:menu.radio value="manual">Manual order</flux:menu.radio>
                        <flux:menu.radio value="updated">Recently updated</flux:menu.radio>
                        <flux:menu.radio value="created">Recently created</flux:menu.radio>
                        <flux:menu.radio value="priority">Priority</flux:menu.radio>
                        <flux:menu.radio value="due_date">Due date</flux:menu.radio>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown> --}}

            <flux:modal.trigger name="ticket-filters" wire:click="syncDraftFilters">
                <div class="relative">
                    <flux:button
                        type="button"
                        icon="funnel"
                        size="sm"
                        title="{{ count($filters) ? count($filters).' active filters' : 'Filter tickets' }}"
                        aria-label="{{ count($filters) ? 'Filter tickets, '.count($filters).' active' : 'Filter tickets' }}"
                    >
                        Filter
                    </flux:button>

                    @if (count($filters))
                        <span class="pointer-events-none absolute -right-1.25 -top-1.25 flex size-3.5 items-center justify-center rounded-full bg-accent text-[9px] font-semibold text-accent-foreground ring-2 ring-white dark:ring-neutral-900">
                            {{ count($filters) }}
                        </span>
                    @endif
                </div>
            </flux:modal.trigger>
        </div>
    </div>

    @if (count($filters))
        <div class="mt-2 flex min-w-0 flex-wrap items-center gap-1.5 border-t border-neutral-200 px-1 pt-2 dark:border-neutral-700">
            <div class="mb-0.5 flex w-full items-center justify-between sm:hidden">
                <span class="text-xs font-medium text-neutral-500 dark:text-neutral-400">Active filters</span>

                <button type="button" wire:click="clearFilters" class="text-xs font-medium text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-white">
                    Clear all
                </button>
            </div>

            <span class="mr-1 hidden text-xs font-medium text-neutral-500 sm:inline dark:text-neutral-400">Active filters</span>

            <div class="flex flex-wrap pb-1 sm:pb-0 gap-1.5">
                @foreach ($filters as $filter)
                    @php($filter_label = $this->ticketFilterLabel($filter))

                    <button
                        type="button"
                        wire:key="active-filter-{{ $filter }}"
                        wire:click="removeFilter('{{ $filter }}')"
                        class="inline-flex h-6 items-center gap-1 rounded-md bg-violet-50 border border-violet-200 dark:border-violet-800 shadow-xs pl-2 pr-1.5 text-xs font-medium text-violet-600 dark:text-violet-400 transition ease-in-out duration-100 hover:bg-violet-100 dark:bg-violet-900/20 dark:hover:bg-violet-900/50"
                        aria-label="Remove {{ $filter_label }} filter"
                    >
                        {{ $filter_label }}
                        <flux:icon.x-mark variant="micro" class="size-3.5 opacity-60" />
                    </button>
                @endforeach
            </div>

            <button type="button" wire:click="clearFilters" class="ml-auto hidden text-xs font-medium text-neutral-500 hover:text-neutral-800 sm:block dark:text-neutral-400 dark:hover:text-white">
                Clear all
            </button>
        </div>
    @endif

    <flux:modal name="ticket-filters" class="w-90! max-w-90! sm:w-180! sm:max-w-200!">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Filter tickets</flux:heading>
                <flux:text class="mt-1">Narrow the list by status, priority, tag, project, and release.</flux:text>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <fieldset class="space-y-3">
                    <legend class="text-sm font-medium text-neutral-900 dark:text-white">Status</legend>

                    <div class="space-y-2.5">
                        @foreach (Status::cases() as $status)
                            <flux:checkbox
                                wire:model="draft_filters"
                                wire:key="status-filter-{{ $status->value }}"
                                :value="$status->value"
                                :label="$status->label()"
                            />
                        @endforeach
                    </div>
                </fieldset>

                <fieldset class="space-y-3">
                    <legend class="text-sm font-medium text-neutral-900 dark:text-white">Priority</legend>

                    <div class="space-y-2.5">
                        @foreach (Priority::cases() as $priority)
                            <flux:checkbox
                                wire:model="draft_filters"
                                wire:key="priority-filter-{{ $priority->value }}"
                                :value="$priority->value"
                                :label="$priority->label()"
                            />
                        @endforeach
                    </div>
                </fieldset>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <fieldset class="space-y-3">
                    <legend class="text-sm font-medium text-neutral-900 dark:text-white">Tags</legend>

                    <div class="max-h-40 space-y-2.5 overflow-y-auto pt-0.5">
                        @forelse ($this->ticketFilterTags() as $tag)
                            <flux:checkbox wire:model="draft_filters" wire:key="tag-filter-{{ $tag->id }}" value="tag:{{ $tag->name }}" :label="$tag->name" />
                        @empty
                            <flux:text size="sm">No tags</flux:text>
                        @endforelse
                    </div>
                </fieldset>

                @if ($this->showProjectTicketFilter())
                    <fieldset class="space-y-3">
                        <legend class="text-sm font-medium text-neutral-900 dark:text-white">Projects</legend>

                        <div class="max-h-40 space-y-2.5 overflow-y-auto pt-0.5">
                            @foreach ($this->ticketFilterProjects() as $project)
                                <flux:checkbox wire:model="draft_filters" wire:key="project-filter-{{ $project->id }}" value="project:{{ $project->id }}" :label="$project->name" />
                            @endforeach
                        </div>
                    </fieldset>
                @endif

                @if ($this->showReleaseTicketFilter())
                    <fieldset class="space-y-3">
                        <legend class="text-sm font-medium text-neutral-900 dark:text-white">Releases</legend>

                        <div class="max-h-40 space-y-2.5 overflow-y-auto pt-0.5">
                            @foreach ($this->ticketFilterReleases() as $release)
                                <flux:checkbox wire:model="draft_filters" wire:key="release-filter-{{ $release->id }}" value="release:{{ $release->id }}" :label="$release->name" />
                            @endforeach
                        </div>
                    </fieldset>
                @endif
            </div>

            <div class="flex items-center justify-between gap-3 border-t border-neutral-200 pt-4 dark:border-neutral-700">
                <flux:button type="button" size="sm" wire:click="$set('draft_filters', [])">
                    Clear all
                </flux:button>

                <flux:modal.close>
                    <flux:button type="button" variant="primary" size="sm" wire:click="applyFilters">
                        Apply filters
                    </flux:button>
                </flux:modal.close>
            </div>
        </div>
    </flux:modal>
</div>
