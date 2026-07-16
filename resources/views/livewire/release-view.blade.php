<div
    x-on:release-ticket-group-updated.window="$wire.$island('release-metadata').$refresh()"
    class="border-t sm:border border-neutral-200 dark:border-neutral-700 shadow-xs bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2"
>
    <div class="p-4 sm:w-11/12 max-w-360 space-y-5 mx-auto">
        <div>
            <div class="flex items-center justify-between gap-2.5">
                <h1 class="font-medium truncate">
                    {{ $release->name }}
                </h1>

                <livewire:status-switcher :model="$release" />
            </div>

            @if ($release->description)
                <p class="max-w-2xl mt-2 sm:mt-0.5 text-sm text-neutral-500 dark:text-neutral-400">
                    {{ $release->description }}
                </p>
            @endif
        </div>

        @island(name: 'release-metadata', always: true)
            <div>
                <div class="mb-2 flex items-center justify-between text-xs">
                    <span class="font-medium text-neutral-600 dark:text-neutral-300">
                        {{ $this->stats['completed'] }} of {{ $this->stats['total'] }} tickets completed
                    </span>

                    <span class="font-semibold text-neutral-900 dark:text-white">
                        {{ $this->stats['progress'] }}%
                    </span>
                </div>

                <div class="h-2 overflow-hidden rounded-full bg-neutral-200 dark:bg-neutral-700">
                    <div
                        class="h-full rounded-full bg-neutral-900 dark:bg-white"
                        style="width: {{ min($this->stats['progress'], 100) }}%"
                    ></div>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-3">
                <div class="rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 shadow-xs p-3">
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                        Started
                    </p>

                    <p class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">
                        {{ $release->start_date?->format('M j, Y') ?? 'Not set' }}
                    </p>
                </div>

                <div class="rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 shadow-xs p-3">
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                        Due
                    </p>

                    <p class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">
                        {{ $release->due_date?->format('M j, Y') ?? 'Not set' }}
                    </p>
                </div>

                <div class="rounded-lg border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 shadow-xs p-3">
                    <p class="text-xs text-neutral-500 dark:text-neutral-400">
                        Remaining
                    </p>

                    <p class="mt-1 text-sm font-medium text-neutral-900 dark:text-white">
                        {{ $this->stats['remaining'] }}
                        {{ Str::plural('ticket', $this->stats['remaining']) }}
                    </p>
                </div>
            </div>
        @endisland
    </div>

    <flux:separator class="bg-neutral-200! dark:bg-neutral-700!" />

    <livewire:release-ticket-list :$release />
</div>
