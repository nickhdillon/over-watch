<div class="border-t sm:border border-neutral-200 sm:space-y-6 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
    <section class="p-4 sm:pt-12 mx-auto sm:w-11/12 max-w-5xl">
        @include('partials.settings-heading')

        <flux:heading class="sr-only">{{ __('Profile settings') }}</flux:heading>

        <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
            <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
                @livewire('avatar')
                
                <flux:input wire:model="name" :label="__('Name')" type="text" required autofocus autocomplete="name" />

                <div>
                    <flux:input wire:model="email" :label="__('Email')" type="email" required autocomplete="email" />

                    @if ($this->hasUnverifiedEmail)
                        <div>
                            <flux:text class="mt-4">
                                {{ __('Your email address is unverified.') }}

                                <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                    {{ __('Click here to re-send the verification email.') }}
                                </flux:link>
                            </flux:text>

                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                    <flux:button variant="primary" type="submit" size="sm">{{ __('Save') }}</flux:button>
                </div>
            </form>

            @if ($this->showDeleteUser)
                <livewire:settings.delete-user-form />
            @endif
        </x-settings.layout>
    </section>
</div>
