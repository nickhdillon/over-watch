<div>
    <div class="border-t sm:border border-neutral-200 dark:border-neutral-700 shadow-xs bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
        <div class="p-4 sm:py-12 mx-auto sm:w-3/4 max-w-5xl">
            <x-card heading="Settings" supporting_text="Configure your release's basic settings.">
                <x-slot:content>
                    <form wire:submit='save' class="space-y-6 p-4">
                        @if (!$project && !$release)
                            <flux:field>
                                <flux:label>Project</flux:label>

                                <flux:select
                                    variant="listbox"
                                    placeholder="Select a project"
                                    wire:model.live='project_id'
                                    :disabled="$project || $release"
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
                            @if ($release)
                                <div>
                                    <flux:modal.trigger name="delete-release-{{ $release->id }}">
                                        <flux:button variant="danger" size="sm">
                                            Delete
                                        </flux:button>
                                    </flux:modal.trigger>

                                    <flux:modal name="delete-release-{{ $release->id }}" class="w-90! sm:w-120!">
                                        <div class="space-y-6 text-left">
                                            <div class="space-y-4!">
                                                <flux:heading size="lg" class="font-semibold -mt-1.5!">
                                                    Delete Release?
                                                </flux:heading>

                                                <flux:subheading>
                                                    Are you sure you want to delete this release?
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
                            @endif

                            <div class="ml-auto space-x-1">
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
                        </div>
                    </form>
                </x-slot:content>
            </x-card>
        </div>
    </div>
</div>
