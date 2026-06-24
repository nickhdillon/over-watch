@use('App\Enums\Priority', 'Priority')

<div x-data="{ open: false }" class="relative">
    <button
        type="button"
        x-on:click="open = ! open"
		x-on:click.outside="open = false"
        x-ref="button"
        class="flex items-center justify-between gap-0.75 w-full rounded-sm font-medium text-xs p-1 hover:bg-neutral-200 dark:hover:bg-neutral-700"
    >
        <flux:icon :icon="$priority->icon()" class="size-4 {{ $priority->color() }} stroke-[2.5px]" />
    </button>

    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        x-anchor.bottom-start="$refs.button"
        class="absolute z-10 mt-1 max-h-60 w-42 sm:w-52 overflow-y-auto rounded-lg border border-neutral-300 bg-white p-1 shadow-md dark:border-neutral-700 dark:bg-neutral-800"
    >
        @foreach (Priority::cases() as $option)
            <button
                type="button"
                wire:click="$set('priority', '{{ $option }}')"
                x-on:click="open = false"
                class="flex w-full items-center gap-2 rounded-sm px-1.75 py-1.25 text-left text-sm {{ $option->bgHoverColors() }}"
            >
                <div class="flex items-center gap-2">
                    <flux:icon :icon="$option->icon()" class="size-4 {{ $option->color() }} stroke-[2.5px]" />

                    <span class="font-medium">{{ $option->label() }}</span>
                </div>

                @if (($option instanceof Priority ? $option : $option) === $priority)
                    <flux:spacer />

                    <flux:icon.check class="size-4 {{ $option->color() }} stroke-[2.5px]" />
                @endif
            </button>
        @endforeach
    </div>
</div>
