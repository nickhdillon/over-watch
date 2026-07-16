<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <flux:navlist aria-label="{{ __('Settings') }}">
            <flux:navlist.item
                :href="route('profile.edit')"
                wire:navigate
                class="data-current:bg-violet-50! data-current:text-violet-700! dark:data-current:bg-violet-500/10! dark:data-current:text-violet-300!"
            >
                {{ __('Profile') }}
            </flux:navlist.item>

            <flux:navlist.item
                :href="route('security.edit')"
                wire:navigate
                class="data-current:bg-violet-50! data-current:text-violet-700! dark:data-current:bg-violet-500/10! dark:data-current:text-violet-300!"
            >
                {{ __('Security') }}
            </flux:navlist.item>

            <flux:navlist.item
                :href="route('appearance.edit')"
                wire:navigate
                class="data-current:bg-violet-50! data-current:text-violet-700! dark:data-current:bg-violet-500/10! dark:data-current:text-violet-300!"
            >
                {{ __('Appearance') }}
            </flux:navlist.item>
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
