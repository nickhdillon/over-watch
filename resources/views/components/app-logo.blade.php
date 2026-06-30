@props([
    'sidebar' => false,
])

@if ($sidebar)
    <flux:sidebar.brand name="Overwatch" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-5 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    @if (! request()->routeIs('project.*', 'release.*')) 
        <flux:brand name="Overwatch" {{ $attributes }} class="flex sm:hidden">
            <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md">
                <x-app-logo-icon />
            </x-slot>
        </flux:brand>
    @endif

    <flux:brand name="Overwatch" {{ $attributes }} class="hidden sm:flex">
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md">
            <x-app-logo-icon />
        </x-slot>
    </flux:brand>
@endif
