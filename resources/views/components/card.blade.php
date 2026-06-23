@props(['heading' => null, 'supporting_text' => null, 'button' => null, 'content'])

<div {{ $attributes->merge(['class' => 'flex flex-col bg-neutral-50 dark:bg-neutral-800 border border-neutral-200 dark:border-white/10 rounded-xl']) }}>
    @if ($heading)
        <div class="flex w-full items-center px-4.5 py-2.5 justify-between shrink-0">
            <div>
                <flux:heading>
                    {!! $heading !!}
                </flux:heading>

                @if ($supporting_text) 
                    <flux:text class="mt-1">{{ $supporting_text }}</flux:text>
                @endif
            </div>

            @if ($button)
                {{ $button }}
            @endif
        </div>
    @endif

    <div class="flex flex-col grow min-h-0 bg-white dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 m-[2.5px] rounded-lg inset-shadow-xs">
        {{ $content }}
    </div>
</div>
