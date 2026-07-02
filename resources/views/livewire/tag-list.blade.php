<div>
    <div class="border-t sm:border border-neutral-200 space-y-3 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
        <div class="p-4 sm:py-12 mx-auto sm:w-11/12 max-w-5xl">
            <div class="flex items-center justify-between gap-2 mb-4">
                <h1 class="font-medium">Tags</h1>

                <div>
                    <flux:modal.trigger x-on:click="$flux.modal('tag-form').show()">
                        <flux:button
                            variant="primary"
                            icon="plus"
                            size="sm"
                        >
                            New tag
                        </flux:button>
                    </flux:modal.trigger>
                </div>
            </div>
            
            <div class="border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 shadow-xs">
                @forelse ($this->tags as $tag)
                    <div
                        wire:key='{{ $tag->id }}'
                        class="group relative first:rounded-t-lg last:rounded-b-lg hover:bg-neutral-50 dark:hover:bg-neutral-800"
                    >
                        <button
                            aria-label="View {{ $tag->name }}"
                            class="absolute inset-0 z-10 rounded-[inherit]"
                            x-on:click="$dispatch('load-tag', { tag_id: {{ $tag->id }} })"
                        ></button>

                        <div class="pointer-events-none flex items-center justify-between gap-3 p-3.5">
                            <p class="text-sm font-medium text-neutral-700 dark:text-white">
                                {{ $tag->name }}
                            </p>

                            <div class="flex items-center gap-2.5">
                                <div class="{{ $tag->color->background() }} size-3 sm:size-3.5 rounded-[3px]"></div>
                                <p class="text-sm">{{ $tag->color->label() }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-4 text-center text-sm text-neutral-500 dark:text-neutral-400">
                        No tags
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <livewire:tag-form :$project />
</div>
