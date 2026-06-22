<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900">
        <flux:header class="px-4! space-x-2!">
            <x-app-logo href="{{ route('dashboard') }}" wire:navigate class="hidden sm:flex" />

            @if (request()->routeIs('project*')) 
                <x-project-switcher />
            @endif

            <flux:spacer />

            <x-desktop-user-menu />
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
