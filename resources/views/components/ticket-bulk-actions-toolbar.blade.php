@props(['releases'])

<div
    x-cloak
    x-show="selectedTickets.length"
    x-transition
    class="fixed bottom-6 left-1/2 z-50 flex -translate-x-1/2 items-center gap-2 rounded-xl border border-neutral-200 bg-white pl-4 pr-2 py-1.5 shadow-lg dark:border-neutral-700 dark:bg-neutral-900"
>
    <p class="flex items-center gap-1.5 text-sm font-medium text-neutral-700 dark:text-neutral-200">
        <span
            x-text="selectedTickets.length"
            class="inline-flex size-5 items-center justify-center rounded-full bg-neutral-200/75 text-xs dark:bg-neutral-700"
        ></span>

        selected
    </p>

    <flux:button
        type="button"
        variant="ghost"
        size="sm"
        x-on:click="selectedTickets = @js($this->tickets->pluck('id')->map(fn ($id) => (string) $id)->values())"
    >
        Select all
    </flux:button>

    <flux:separator vertical class="my-1.5" />

    <div class="flex items-center">
        <flux:dropdown>
            <flux:button
                size="sm"
                variant="ghost"
                icon="flag"
            >
                Add to release
            </flux:button>

            <flux:menu>
                @foreach ($this->releases as $release)
                    <flux:menu.item
                        x-on:click="
                            $wire.addTicketsToRelease(
                                selectedTickets,
                                {{ $release->id }},
                                '{{ $release->name }}'
                            );

                            clearSelection();
                        "
                        class="flex items-center gap-2"
                    >
                        <flux:badge :color="$release->project->color->value" size="sm">
                            {{ $release->project->key }}
                        </flux:badge>

                        <span>
                            {{ $release->name }}
                        </span>
                    </flux:menu.item>
                @endforeach
            </flux:menu>
        </flux:dropdown>

        <flux:button
            variant="ghost"
            size="sm"
            class="pl-2!"
            icon="x-mark"
            x-on:click="
                $wire.removeTicketsFromRelease(selectedTickets);
                clearSelection();
            "
        >
            Remove from release
        </flux:button>
    </div>

    <flux:separator vertical class="my-1.5" />

    <flux:button
        type="button"
        variant="ghost"
        size="sm"
        x-on:click="clearSelection()"
    >
        Clear
    </flux:button>
</div>
