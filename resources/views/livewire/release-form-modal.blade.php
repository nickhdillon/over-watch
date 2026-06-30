<div>
    <flux:modal name="new-release" class="w-90! sm:w-xl!" x-on:close="$wire.resetForm()">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">New release</flux:heading>
                <flux:text class="mt-2">Configure your release's basic settings.</flux:text>
            </div>

            <form wire:submit='save' class="space-y-6">
                @if (! $project)
                    <flux:field>
                        <flux:label>Project</flux:label>

                        <flux:select
                            variant="listbox"
                            placeholder="Select a project"
                            wire:model='project_id'
                        >
                            @foreach ($projects as $project)
                                <flux:select.option value="{{ $project->id }}">
                                    {{ $project->name }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>

                        <flux:error name="project_id" />
                    </flux:field>
                @endif

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

                <flux:field>
                    <flux:label>Start Date</flux:label>

                    <flux:date-picker wire:model='start_date' />

                    <flux:error name="start_date" />
                </flux:field>

                <flux:field>
                    <flux:label>Due Date</flux:label>

                    <flux:date-picker wire:model='due_date' />

                    <flux:error name="due_date" />
                </flux:field>

                <div class="flex items-center gap-2">
                    <flux:spacer />

                    <flux:button
                        x-on:click="$flux.modal('new-release').close()"
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
