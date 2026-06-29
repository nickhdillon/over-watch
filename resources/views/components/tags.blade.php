@props(['project_tags', 'tags', 'project_id'])

@php
    $tag_colors = collect($project_tags)->pluck('color', 'name');
@endphp

<flux:field>
    <flux:label>
        Tags
    </flux:label>

    <flux:select
        wire:key="ticket-tags-select-{{ $project_id }}"
        variant="listbox"
        searchable
        multiple
        placeholder="Select tags"
        clearable
        wire:model.live="tags"
    >
        <x-slot name="search">
            <flux:select.search form class="px-4" placeholder="Search tags..." />
        </x-slot>
        
        @foreach ($project_tags as $tag)
            <flux:select.option value="{{ $tag['name'] }}">
                {{ $tag['name'] }}
            </flux:select.option>
        @endforeach
    </flux:select>

    <flux:error name="tags" />

    @if (filled(array_filter($tags)))
        <div class="flex flex-wrap gap-1.5">
            @foreach (array_filter($tags) as $tag)
                <flux:badge
                    size="sm"
                    :color="$tag_colors[$tag] ?? 'emerald'"
                    wire:key="selected-tag-{{ $tag }}"
                >
                    {{ $tag }}

                    <flux:badge.close wire:click="removeTag(@js($tag))" />
                </flux:badge>
            @endforeach
        </div>
    @endif
</flux:field>
