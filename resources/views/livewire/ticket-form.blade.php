<div>
    <flux:modal wire:model.self="show_ticket_form" name="ticket-form" class="w-90! sm:w-xl!" x-on:close="$wire.resetForm()">
        <div class="space-y-6">
            <flux:heading size="lg">
                {{ $ticket ? 'Edit' : 'New' }} Ticket
            </flux:heading>

            <form wire:submit='save' class="space-y-6">
                <flux:field>
                    <flux:label>Project</flux:label>

                    <flux:select variant="listbox" placeholder="Select a project" wire:model='project_id' clearable>
                        @foreach ($projects as $project)
                            <flux:select.option value="{{ $project->id }}">
                                {{ $project->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:error name="project_id" />
                </flux:field>

                <flux:field>
                    <flux:label>Title</flux:label>

                    <flux:input type="text" wire:model='title' required />

                    <flux:error name="title" />
                </flux:field>

                <flux:field>
                    <flux:label>Description</flux:label>

                    <flux:textarea wire:model='description' />

                    <flux:error name="description" />
                </flux:field>

                <div wire:key="ticket-priority-picker-{{ $ticket?->id ?? 'new' }}-{{ $priority->value }}">
                    <x-option-picker
                        label="Priority"
                        model="priority"
                        :value="$priority->value"
                        :options="\App\Enums\Priority::options()"
                        placeholder="Choose priority..."
                    />
                </div>

                <flux:field>
                    <flux:label>Due Date</flux:label>

                    <flux:date-picker wire:model='due_date' />

                    <flux:error name="due_date" />
                </flux:field>

                <div class="flex items-center gap-2">
                    <flux:spacer />

                    <flux:button
                        x-on:click="$flux.modal('{{ $ticket ? ('edit-ticket' . $ticket->id) : 'new-ticket' }}').close()"
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
