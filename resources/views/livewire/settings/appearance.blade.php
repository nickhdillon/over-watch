<div class="border-t sm:border border-neutral-200 sm:space-y-6 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
    <section class="p-4 sm:pt-12 mx-auto sm:w-11/12 max-w-5xl">
        @include('partials.settings-heading')

        <flux:heading class="sr-only">{{ __('Appearance settings') }}</flux:heading>

        <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
            <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
                <flux:radio value="light" icon="sun">{{ __('Light') }}</flux:radio>
                <flux:radio value="dark" icon="moon">{{ __('Dark') }}</flux:radio>
                <flux:radio value="system" icon="computer-desktop">{{ __('System') }}</flux:radio>
            </flux:radio.group>
        </x-settings.layout>
    </section>
</div>
