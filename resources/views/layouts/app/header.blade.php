<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900">
        <div class="flex min-h-screen flex-col">
            <flux:header class="px-6!">
                <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

                @if (request()->routeIs('project.*') && ! request()->routeIs('project.create'))
                    <x-project-switcher />
                @endif

                @if (request()->routeIs('project.release.*'))
                    <x-release-switcher />
                @endif

                <flux:spacer />

                <x-desktop-user-menu />
            </flux:header>

            <x-secondary-navbar />

            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
        @livewireScriptConfig
    </body>
</html>
