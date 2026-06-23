@use('App\Enums\Color', 'Color')
@props(['color'])

<flux:field>
	<flux:label>Color</flux:label>

	<div x-data="{ open: false }" class="relative">
		<button
			type="button"
			x-on:click="open = ! open"
			x-on:click.outside="open = false"
			class="w-full flex border rounded-lg items-center justify-between disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-10 leading-5.5 ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/7 text-neutral-700 disabled:text-neutral-500 placeholder-neutral-400 disabled:placeholder-neutral-400/70 dark:text-neutral-300 dark:disabled:text-neutral-400 dark:placeholder-neutral-400 dark:disabled:placeholder-neutral-500 shadow-xs border-neutral-200 border-b-neutral-300/80 disabled:border-b-neutral-200 dark:border-white/10 dark:disabled:border-white/5 data-invalid:shadow-none data-invalid:border-red-500 dark:data-invalid:border-red-500 disabled:data-invalid:border-red-500 dark:disabled:data-invalid:border-red-500 focus:outline-hidden focus:ring-2 focus:ring-accent focus:ring-offset-2 focus:ring-offset-accent-foreground"
		>
			<div class="flex items-center gap-2">
				@if ($color)
					<span class="size-4 rounded-sm bg-{{ $color instanceof \App\Enums\Color ? $color->value : $color }}-500"></span>
					<span>{{ $color instanceof \App\Enums\Color ? $color->label() : \App\Enums\Color::from($color)->label() }}</span>
				@else
					<span class="text-neutral-500">Choose color...</span>
				@endif
			</div>

			<flux:icon.chevron-down class="size-4 text-neutral-400 stroke-[2.5px]" />
		</button>

		<div
			x-cloak
			x-show="open"
			x-transition:enter="transition ease-out duration-100"
			x-transition:enter-start="opacity-0 -translate-y-1"
			x-transition:enter-end="opacity-100 translate-y-0"
			x-transition:leave="transition ease-in duration-100"
			x-transition:leave-start="opacity-100 translate-y-0"
			x-transition:leave-end="opacity-0 -translate-y-1"
			class="absolute z-10 mt-1.25 max-h-60 w-full overflow-y-auto rounded-lg border border-neutral-300 bg-white p-1 shadow-md dark:border-neutral-700 dark:bg-neutral-800"
		>
			<button
				type="button"
				wire:click="$set('color', null)"
				x-on:click="open = false"
				class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-700"
			>
				<span class="size-4 rounded-sm bg-neutral-200 dark:bg-neutral-500"></span>
				<span>None</span>

				@if ($color === null)
					<flux:spacer />
					<flux:icon.check class="size-4 text-neutral-500 stroke-[2.5px]" />
				@endif
			</button>

			@foreach (Color::cases() as $option)
				<button
					type="button"
					wire:click="$set('color', '{{ $option->value }}')"
					x-on:click="open = false"
					class="flex w-full items-center gap-2 rounded-sm px-2 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-700"
				>
					<span class="size-4 rounded-sm bg-{{ $option->value }}-500"></span>
					<span>{{ $option->label() }}</span>

					@if (($color instanceof \App\Enums\Color ? $color->value : $color) === $option->value)
						<flux:spacer />
						<flux:icon.check class="size-4 text-neutral-500 stroke-[2.5px]" />
					@endif
				</button>
			@endforeach
		</div>
	</div>

	<flux:error name="color" />
</flux:field>
