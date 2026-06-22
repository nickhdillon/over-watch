<x-layouts::app.header :title="$title ?? null">
    <flux:main class="px-4! pb-4! pt-0!">
        {{ $slot }}
    </flux:main>
</x-layouts::app.header>
