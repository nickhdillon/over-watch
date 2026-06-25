@use('App\Enums\Status', 'Status')
@use('App\Enums\Color', 'Color')

<div x-data="{ statusModalOpen: false }">
    <button
        type="button"
        x-on:click.prevent.stop="statusModalOpen = ! statusModalOpen"
		x-on:click.outside="statusModalOpen = false"
		x-on:click.away="statusModalOpen = false"
        x-ref="button"
        class="flex items-center justify-between gap-0.75 w-full rounded-sm font-medium text-xs px-1.5 py-0.5 sm:px-1.5 sm:py-0.5 {{ $status->textColors() }} {{ $status->bgColors() }} {{ $status->borderColors() }}"
    >
        <p>{{ $status->label() }}</p>

        <flux:icon.chevron-down class="size-3.5 stroke-[2px] {{ $status->textColors() }}" />
    </button>

    <template x-teleport="body">
        <div
            x-cloak
            x-show="statusModalOpen"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0 -translate-y-1"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-1"
            x-anchor.bottom-start="$refs.button"
            x-on:click.outside="statusModalOpen = false"
            x-on:click.away="statusModalOpen = false"
            x-on:keydown.escape.window="statusModalOpen = false"
            class="z-50 mt-1 max-h-60 w-42 sm:w-52 overflow-y-auto rounded-lg border border-neutral-300 bg-white p-1 shadow-md dark:border-neutral-700 dark:bg-neutral-800"
        >
            @foreach (Status::cases() as $option)
                <button
                    type="button"
                    wire:click="$set('status', '{{ $option }}')"
                    x-on:click="statusModalOpen = false"
                    class="flex w-full items-center gap-2 rounded-md px-1.75 py-1.25 text-left text-sm {{ $option->textColors() }} {{ $option->bgHoverColors() }}"
                >
                    <div class="flex items-center gap-2">
                        <div class="{{ $option->indicatorColors() }} size-3.5 rounded-sm"></div>

                        <span class="font-medium">{{ $option->label() }}</span>
                    </div>

                    @if (($option instanceof Status ? $option : $option) === $status)
                        <flux:spacer />

                        <flux:icon.check class="size-4 {{ $option->textColors() }} stroke-[2.5px]" />
                    @endif
                </button>
            @endforeach
        </div>
    </template>
</div>
