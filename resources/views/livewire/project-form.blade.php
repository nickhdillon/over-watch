<div class="pt-4 mx-auto w-3/4">
    <x-card heading="Settings" supporting_text="Configure your project's basic settings.">
        <x-slot:content>
            <form wire:submit='save' class="space-y-6 p-4">
                <flux:field>
                    <flux:label>Name</flux:label>

                    <flux:input type="text" wire:model='name' required />

                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Description</flux:label>

                    <flux:textarea wire:model='description' />

                    <flux:error name="description" />
                </flux:field>

                <x-color-picker :$color />

                <flux:field>
                    <flux:label for="image">Image</flux:label>

                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-4">
                            <input id="image" type="file" wire:model="image" class="hidden">

                            <label for="image" class="cursor-pointer">
                                <div class="relative flex items-center justify-center size-20 rounded-lg transition-colors cursor-pointer border border-neutral-200 dark:border-white/10 hover:border-neutral-300 dark:hover:border-white/10 bg-neutral-100 hover:bg-neutral-200 dark:bg-white/10 hover:dark:bg-white/15 in-data-dragging:dark:bg-white/15">
                                    @if ($image)
                                        <img
                                            src="{{ $image?->temporaryUrl() }}"
                                            alt="Project Image"
                                            class="size-full rounded-lg object-cover"
                                        />
                                    @elseif ($image_path)
                                        <img
                                            src="{{ Storage::disk('s3')->url($image_path) }}"
                                            alt="{{ $name }}"
                                            class="size-full rounded-lg object-cover"
                                        />
                                    @else
                                        <flux:icon name="camera" />
                                    @endif
                                </div>
                            </label>

                            @if ($project && $image_path)
                                <label for="image">
                                    <span class="inline-flex h-8 cursor-pointer items-center rounded-lg px-3 text-sm shadow-xs font-medium bg-white hover:bg-neutral-50 dark:bg-neutral-700 dark:hover:bg-neutral-600/75 text-neutral-800 dark:text-white border border-neutral-200 hover:border-neutral-200 border-b-neutral-300/80 dark:border-neutral-600 dark:hover:border-neutral-600">
                                        Upload image
                                    </span>
                                </label>
                            @endif
                        </div>

                        @if ($project && $image_path)
                            <flux:button icon="trash" icon:variant="outline" variant="ghost" type="button" class="h-8! w-8! shrink-0!" />
                        @endif
                    </div>

                    <flux:error name="image" />
                </flux:field>

                <div class="flex items-center gap-2">
                    <flux:spacer />

                    <flux:button
                        href="/"
                        wire:navigate
                        type="button"
                        size="sm"
                        variant="ghost"
                    >
                        Cancel
                    </flux:button>

                    <flux:button
                        type="submit"
                        size="sm"
                        variant="primary"
                        class="px-4!"
                    >
                        Save
                    </flux:button>
                </div>
            </form>
        </x-slot:content>
    </x-card>
</div>
