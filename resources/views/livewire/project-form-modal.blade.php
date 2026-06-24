<div>
    <flux:modal name="new-project" class="w-90! sm:w-xl!">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">New project</flux:heading>
                <flux:text class="mt-2">Configure your project's basic settings.</flux:text>
            </div>

            <form wire:submit='save' class="space-y-6">
                <flux:field>
                    <flux:label>Name</flux:label>

                    <flux:input type="text" wire:model='name' required />

                    <flux:error name="name" />
                </flux:field>

                <flux:field>
                    <flux:label>URL</flux:label>

                    <flux:input type="text" wire:model='url' />

                    <flux:error name="url" />
                </flux:field>

                <flux:field>
                    <flux:label>Description</flux:label>

                    <flux:textarea wire:model='description' />

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
