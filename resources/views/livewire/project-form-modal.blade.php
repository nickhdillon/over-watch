<div
    x-data="{
        name: @entangle('name'),
        key: @entangle('key'),
        keyCustomized: false,

        suggestKey(value) {
            return value
                .toUpperCase()
                .replace(/[^A-Z0-9]/g, '')
                .slice(0, 4);
        },
    }"
    x-effect="if (! keyCustomized) key = suggestKey(name);"
>
    <flux:modal name="new-project" class="w-90! sm:w-xl!" x-on:close="$wire.resetForm()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">New project</flux:heading>
                <flux:text class="mt-2">Configure your project's basic settings.</flux:text>
            </div>

            <form wire:submit='save' class="space-y-6">
                <flux:field>
                    <flux:label>Name</flux:label>

                    <flux:input type="text" x-model='name' required />

                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>Key</flux:label>

                    <flux:input
                        type="text"
                        x-model='key'
                        x-on:input="keyCustomized = true; key = suggestKey(key)"
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

                <div class="flex items-center gap-2">
                    <flux:spacer />

                    <flux:button
                        x-on:click="$flux.modal('new-project').close()"
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
        </div>
    </flux:modal>
</div>
