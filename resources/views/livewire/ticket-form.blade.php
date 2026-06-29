<div>
    <flux:modal wire:model.self="show_ticket_form" name="ticket-form" class="w-90! sm:w-xl!" x-on:close="$wire.resetForm()">
        <div class="space-y-6">
            <flux:heading size="lg" class="flex items-center gap-2">
                {{ $ticket ? 'Edit Ticket' : 'New Ticket' }}

                @if ($ticket)
                    <flux:badge :color="$ticket->project?->color->value" size="sm">
                        {{ $ticket->issue_key }}
                    </flux:badge>
                @endif
            </flux:heading>

            <form wire:submit='save' class="space-y-6">
                @if (!$project && !$ticket)
                    <flux:field>
                        <flux:label>Project</flux:label>

                        <flux:select
                            variant="listbox"
                            placeholder="Select a project"
                            wire:model.live='project_id'
                            :disabled="$project || $ticket"
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

                <x-tags :$project_tags :$tags :$project_id />

                <flux:field>
                    <flux:label>Due Date</flux:label>

                    <flux:date-picker wire:model='due_date' />

                    <flux:error name="due_date" />
                </flux:field>

                <div class="flex items-center justify-between gap-2">
                    @if ($ticket) 
                        <div>
                            <flux:modal.trigger name="delete-ticket-{{ $ticket->id }}">
                                <flux:button variant="danger" size="sm">
                                    Delete
                                </flux:button>
                            </flux:modal.trigger>

                            <flux:modal name="delete-ticket-{{ $ticket->id }}" class="w-90! sm:w-120!">
                                <div class="space-y-6 text-left">
                                    <div class="space-y-4!">
                                        <flux:heading size="lg" class="font-semibold -mt-1.5!">
                                            Delete Ticket?
                                        </flux:heading>

                                        <flux:subheading>
                                            Are you sure you want to delete this ticket?
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
                            x-on:click="$flux.modal('ticket-form').close()"
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
        </div>
    </flux:modal>
</div>
