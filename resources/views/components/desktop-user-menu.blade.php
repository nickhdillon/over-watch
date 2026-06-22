<flux:dropdown position="bottom" align="start">
    @if (auth()->user()->avatar)
        <flux:sidebar.profile
            circle
            :chevron="false"
            :initials="auth()->user()->initials()"
            :avatar="Storage::disk('s3')->url('avatars/' . auth()->user()->avatar)"
            data-test="sidebar-menu-button"
        />
    @else
        <flux:sidebar.profile
            circle
            :chevron="false"
            :initials="auth()->user()->initials()"
            data-test="sidebar-menu-button"
        />
    @endif

    <flux:menu>
        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
            @if (auth()->user()->avatar)
                <flux:avatar
                    circle
                    size="sm"
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    :src="Storage::disk('s3')->url('avatars/' . auth()->user()->avatar)"
                />
            @else
                <flux:avatar
                    circle
                    size="sm"
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                />
            @endif

            <div class="grid flex-1 -space-y-0.5 text-start text-sm leading-tight">
                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                <flux:text class="truncate" size="sm">{{ auth()->user()->email }}</flux:text>
            </div>
        </div>
        <flux:menu.separator />
        <flux:menu.radio.group>
            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                {{ __('Settings') }}
            </flux:menu.item>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item
                    as="button"
                    type="submit"
                    icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer"
                    data-test="logout-button"
                >
                    {{ __('Log out') }}
                </flux:menu.item>
            </form>
        </flux:menu.radio.group>
    </flux:menu>
</flux:dropdown>
