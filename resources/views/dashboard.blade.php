<x-layouts::app :title="__('Dashboard')">
    <div class="border border-neutral-200 space-y-3 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 rounded-lg min-h-screen p-4">
        <h1>Recent projects</h1>
        
        <div class="border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 shadow-xs">
            @foreach ($recent_projects as $project)
                <a
                    href="{{ route('project', $project) }}"
                    wire:navigate
                    class="flex items-center gap-3 hover:bg-neutral-50 dark:hover:bg-neutral-800 p-3.5 first:rounded-t-xl last:rounded-b-xl"
                >
                    <div class="flex size-7 sm:size-8 items-center justify-center rounded-sm bg-{{ $project->color }}-500">
                        <span class="text-md font-medium text-white">
                            {{ Str::substr($project->name, 0, 1) }}
                        </span>
                    </div>

                    <span class="text-sm font-medium text-neutral-700 dark:text-white">
                        {{ $project->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</x-layouts::app>
