<x-layouts::app :title="__('Dashboard')">
    <div class="border border-neutral-200 space-y-3 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 rounded-lg min-h-screen p-4">
        <h1>Recent projects</h1>
        
        <div class="border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 shadow-xs">
            @foreach ($recent_projects as $project)
                <a
                    href="{{ route('project.view', $project) }}"
                    wire:navigate
                    class="flex items-center gap-3 hover:bg-neutral-50 dark:hover:bg-neutral-800 p-3.5 first:rounded-t-xl last:rounded-b-xl"
                >
                    @if (! $project->image_path)
                        <div class="flex relative size-7 sm:size-8 items-center justify-center rounded-sm bg-neutral-400 dark:bg-neutral-600 border border-neutral-200 dark:border-white/10">
                            <span class="text-md font-medium text-white">
                                {{ Str::substr($project->name, 0, 1) }}
                            </span>

                            @if ($project->color) 
                                <div class="absolute -bottom-0.75 -right-0.75 bg-{{ $project->color }}-500 size-1.75 rounded-xs"></div>
                            @endif
                        </div>
                    @else
                        <div class="flex relative size-7 sm:size-8 items-center justify-center rounded-sm border border-neutral-200 dark:border-white/10">
                            <img
                                src="{{ Storage::disk('s3')->url($project->image_path) }}"
                                alt="{{ $project->name }}"
                                class="rounded-sm"
                            />

                            @if ($project->color) 
                                <div class="absolute -bottom-0.75 -right-0.75 bg-{{ $project->color }}-500 size-1.75 rounded-xs"></div>
                            @endif
                        </div>
                    @endif

                    <span class="text-sm font-medium text-neutral-700 dark:text-white">
                        {{ $project->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</x-layouts::app>
