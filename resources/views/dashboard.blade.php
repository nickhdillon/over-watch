<x-layouts::app :title="__('Dashboard')">
    <flux:navbar class="px-4">
        <flux:navbar.item href="/" :current="request()->is('/')">
            Overview
        </flux:navbar.item>

        <flux:navbar.item href="#">
            Projects
        </flux:navbar.item>

        <flux:navbar.item href="#">Tickets</flux:navbar.item>
    </flux:navbar>

    <div class="border-t sm:border border-neutral-200 space-y-3 shadow-xs dark:border-neutral-700 bg-neutral-50/50 dark:bg-neutral-800/20 sm:rounded-lg min-h-screen sm:mx-2 sm:mb-2">
        <div class="p-4 sm:py-12 mx-auto sm:w-11/12">
            <h1 class="mb-4 font-medium">Recent projects</h1>
            
            <div class="border border-neutral-300 dark:border-neutral-700 bg-white/50 dark:bg-neutral-800/50 rounded-lg divide-y divide-neutral-200 dark:divide-neutral-700 shadow-xs">
                @foreach ($recent_projects as $project)
                    <a
                        href="{{ route('project.view', $project) }}"
                        wire:navigate
                        class="group flex items-center gap-3 hover:bg-neutral-50 dark:hover:bg-neutral-800 p-3.5 first:rounded-t-xl last:rounded-b-xl"
                    >
                        @if (! $project->image_path)
                            <div class="flex relative size-7 sm:size-8 items-center justify-center rounded-sm bg-neutral-400 dark:bg-neutral-600 border border-neutral-200 dark:border-white/10">
                                <span class="text-md font-medium text-white">
                                    {{ Str::of($project->name)->substr(0, 1)->upper() }}
                                </span>

                                @if ($project->color) 
                                    <div class="absolute -bottom-1.75 -right-1.75 p-0.75 sm:p-[3.5px] bg-white group-hover:bg-neutral-50 dark:bg-[#202020] dark:group-hover:bg-neutral-800 rounded-sm">
                                        <div class="bg-{{ $project->color }}-500 size-2 sm:size-2.5 rounded-xs"></div>
                                    </div>
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
                                    <div class="absolute -bottom-1.75 -right-1.75 p-0.75 sm:p-[3.5px] bg-white group-hover:bg-neutral-50 dark:bg-[#202020] dark:group-hover:bg-neutral-800 rounded-sm">
                                        <div class="bg-{{ $project->color }}-500 size-2 sm:size-2.5 rounded-xs"></div>
                                    </div>
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
    </div>
</x-layouts::app>
