<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900">
        <flux:header class="px-6! space-x-2!">
            <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

            @if (request()->routeIs('project*') && ! request()->routeIs('project.create'))
                <x-project-switcher />
            @endif

            <flux:spacer />

            <div>
                <flux:modal.trigger name="search" shortcut="cmd.k" class="hidden sm:block">
                    <flux:input as="button" placeholder="Search..." icon="magnifying-glass" kbd="⌘K" size="sm" class="w-50!" />
                </flux:modal.trigger>

                <flux:modal.trigger name="search" shortcut="cmd.k" class="sm:hidden">
                    <flux:icon.magnifying-glass class="text-neutral-400 size-5" />
                </flux:modal.trigger>

                <flux:modal name="search" variant="bare" class="w-full max-w-[92%] sm:max-w-120 my-[12vh] max-h-screen overflow-y-hidden">
                    <flux:command class="border-none shadow-lg inline-flex flex-col max-h-[76vh]">
                        <flux:command.input placeholder="Search..." closable />

                        <flux:command.items>
                            <flux:command.item icon="user-plus" kbd="⌘A">Assign to…</flux:command.item>
                            <flux:command.item icon="document-plus">Create new file</flux:command.item>
                            <flux:command.item icon="folder-plus" kbd="⌘⇧N">Create new project</flux:command.item>
                            <flux:command.item icon="book-open">Documentation</flux:command.item>
                            <flux:command.item icon="newspaper">Changelog</flux:command.item>
                            <flux:command.item icon="cog-6-tooth" kbd="⌘,">Settings</flux:command.item>
                        </flux:command.items>
                    </flux:command>
                </flux:modal>
            </div>

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
