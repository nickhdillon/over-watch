<div>
    <flux:modal wire:model.self="show_tag_form" name="tag-form" class="w-90! sm:w-120! overflow-visible" x-on:close="$wire.resetForm()">
        <div class="space-y-6">
            <flux:heading size="lg">
                {{ $tag ? 'Edit' : 'New' }} Tag
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
                    <flux:label>Name</flux:label>

                    <flux:input type="text" wire:model='name' required />

                    <flux:error name="name" />
                </flux:field>

                <div wire:key="tag-color-picker-{{ $tag?->id ?? 'new' }}-{{ $color?->value }}">
                    <x-option-picker
                        label="Color"
                        model="color"
                        :value="$color?->value"
                        :options="\App\Enums\Color::options()"
                        placeholder="Choose color..."
                    />
                </div>

                <div class="flex items-center gap-2">
                    <flux:spacer />

                    <flux:button
                        x-on:click="$flux.modal('tag-form').close()"
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
