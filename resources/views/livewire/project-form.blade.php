<div>
    <div class="border-t sm:border border-neutral-200 dark:border-neutral-700 shadow-xs bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
        <div class="p-4 sm:py-12 mx-auto sm:w-3/4 max-w-5xl">
            <x-card heading="Settings" supporting_text="Configure your project's basic settings.">
                <x-slot:content>
                    <form wire:submit='save' class="space-y-6 p-4">
                        <flux:field>
                            <flux:label>Name</flux:label>

                            <flux:input type="text" wire:model="name" required />

                            <flux:error name="name" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Key</flux:label>

                            <flux:input
                                type="text"
                                wire:model="key"
                                maxlength="4"
                                required
                            />

                            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                A short identifier for tickets (e.g. <span class="font-medium">PUR</span>, <span class="font-medium">AUD</span>, <span class="font-medium">MOV</span>).
                            </p>

                            <flux:error name="key" />
                        </flux:field>

                        <flux:field>
                            <flux:label>URL</flux:label>

                            <flux:input type="text" wire:model='url' />

                            <flux:error name="url" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Repository URL</flux:label>

                            <flux:input type="text" wire:model='repository_url' />

                            <flux:error name="repository_url" />
                        </flux:field>

                        <flux:field>
                            <flux:label>Description</flux:label>

                            <flux:editor
                                toolbar="heading | bold italic underline strike | bullet ordered blockquote | link | code ~ undo redo"
                                wire:model='description'
                                class="overflow-scroll!"
                            />

                            <flux:error name="description" />
                        </flux:field>

                        <x-option-picker
                            label="Color"
                            model="color"
                            :value="$color"
                            :options="\App\Enums\Color::options()"
                            placeholder="Choose color..."
                            nullable
                        />

                        <x-option-picker
                            label="Priority"
                            model="priority"
                            :value="$priority"
                            :options="\App\Enums\Priority::options()"
                            placeholder="Choose priority..."
                        />

                        <flux:field>
                            <flux:label for="image">Image</flux:label>

                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-4">
                                    <input id="image" type="file" wire:model="image" class="hidden">

                                    <label for="image" class="cursor-pointer">
                                        <div class="group relative flex items-center justify-center size-20 rounded-lg transition-colors cursor-pointer bg-neutral-100 hover:bg-violet-50 in-data-dragging:bg-violet-50 border border-neutral-200 hover:border-violet-200 in-data-dragging:border-violet-200 dark:border-white/10 dark:bg-white/10 dark:hover:border-violet-500/30 dark:hover:bg-violet-500/10 in-data-dragging:dark:border-violet-500/30 in-data-dragging:dark:bg-violet-500/10">
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
                                                <flux:icon name="camera" class="transition-colors group-hover:text-violet-600 dark:group-hover:text-violet-400" />
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
                                    <flux:button icon="trash" icon:variant="outline" variant="ghost" type="button" class="h-8! w-8! shrink-0!" wire:click='deleteImage' />
                                @endif
                            </div>

                            <flux:error name="image" />
                        </flux:field>

                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <flux:modal.trigger name="delete-project-{{ $project->id }}">
                                    <flux:button variant="danger" size="sm">
                                        Delete
                                    </flux:button>
                                </flux:modal.trigger>

                                <flux:modal name="delete-project-{{ $project->id }}" class="w-90! sm:w-120!">
                                    <div class="space-y-6 text-left">
                                        <div class="space-y-4!">
                                            <flux:heading size="lg" class="font-semibold -mt-1.5!">
                                                Delete Project?
                                            </flux:heading>

                                            <flux:subheading>
                                                Are you sure you want to delete this project?
                                            </flux:subheading>
                                        </div>

                                        <div class="flex gap-2">
                                            <flux:spacer />

                                            <flux:modal.close>
                                                <flux:button variant="ghost" size="sm">
                                                    Cancel
                                                </flux:button>
                                            </flux:modal.close>

                                            <flux:button type="button" wire:click="delete" variant="danger" size="sm">
                                                Confirm
                                            </flux:button>
                                        </div>
                                    </div>
                                </flux:modal>
                            </div>

                            <div class="ml-auto space-x-1">
                                <flux:button
                                    :href="route('project.view', $project)"
                                    wire:navigate.hover
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
                        </div>
                    </form>
                </x-slot:content>
            </x-card>
        </div>
    </div>
</div>
