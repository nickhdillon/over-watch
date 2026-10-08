<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="h-dvh overflow-hidden bg-white dark:bg-zinc-900">
        <div class="flex h-full flex-col overflow-y-auto">
            <div class="sticky top-0 z-30 shrink-0">
                <div class="border-b border-neutral-200 bg-white sm:border-none dark:border-neutral-700 dark:bg-zinc-900">
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
                </div>

                <div class="pointer-events-none absolute inset-x-0 -bottom-4 hidden px-2 sm:block">
                    <div class="relative h-4">
                        <div class="absolute top-0 left-0 size-4 bg-white dark:bg-zinc-900"></div>
                        <div class="absolute top-0 right-0 size-4 bg-white dark:bg-zinc-900"></div>

                        <div class="absolute inset-x-4 top-0 h-px bg-neutral-200 dark:bg-neutral-700"></div>

                        <div class="absolute top-0 left-0 size-4 rounded-tl-lg border-t border-l border-neutral-200 bg-neutral-50/50 dark:border-neutral-700 dark:bg-neutral-800/20"></div>
                        <div class="absolute top-0 right-0 size-4 rounded-tr-lg border-t border-r border-neutral-200 bg-neutral-50/50 dark:border-neutral-700 dark:bg-neutral-800/20"></div>
                    </div>
                </div>
            </div>

            <main class="flex flex-1 flex-col sm:px-2">
                <div class="flex-1 bg-neutral-50/50 shadow-xs sm:rounded-lg sm:border sm:border-neutral-200 dark:bg-neutral-800/20 dark:sm:border-neutral-700">
                    {{ $slot }}
                </div>
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
