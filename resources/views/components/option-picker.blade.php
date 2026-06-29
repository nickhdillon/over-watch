<flux:field
    x-data="{
        open: false,
        value: $wire.entangle(@js($model)),
        options: @js($options),

        selected() {
            return this.options.find(option => option.value === this.value);
        },

        choose(value) {
            this.value = value;
            $wire[@js($model)] = value;
            this.open = false;
        },
    }"
>
    <flux:label>{{ $label }}</flux:label>

    <div class="relative">
        <button
            type="button"
            x-on:click="open = ! open"
            x-on:click.outside="open = false"
            class="w-full group flex border rounded-lg items-center justify-between disabled:shadow-none dark:shadow-none appearance-none text-base sm:text-sm py-2 h-[38px] leading-5.5 ps-3 pe-3 bg-white dark:bg-white/10 dark:disabled:bg-white/7 text-neutral-700 disabled:text-neutral-500 placeholder-neutral-400 disabled:placeholder-neutral-400/70 dark:text-neutral-300 dark:disabled:text-neutral-400 dark:placeholder-neutral-400 dark:disabled:placeholder-neutral-500 shadow-xs border-neutral-200 border-b-neutral-300/80 disabled:border-b-neutral-200 dark:border-white/10 dark:disabled:border-white/5 data-invalid:shadow-none data-invalid:border-red-500 dark:data-invalid:border-red-500 disabled:data-invalid:border-red-500 dark:disabled:data-invalid:border-red-500 focus:outline-hidden focus:ring-2 focus:ring-accent focus:ring-offset-2 focus:ring-offset-accent-foreground"
        >
            <div class="flex items-center gap-2">
                <template x-if="selected()?.swatch">
                    <span class="size-4 rounded-sm" :class="selected().swatch"></span>
                </template>

                <template x-if="selected()?.icon">
                    <span :class="selected().color" x-html="selected().icon"></span>
                </template>

                <span
                    x-text="selected()?.label ?? @js($placeholder)"
                    :class="{ 'text-neutral-500': ! selected() }"
                ></span>
            </div>

            <flux:icon.chevron-down class="size-3.5 text-neutral-400/75 group-hover:text-neutral-800 dark:text-white/60 dark:group-hover:text-white stroke-[2.5px]" />
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
            class="absolute z-99 mt-1.25 max-h-50 w-full overflow-y-auto rounded-lg border border-neutral-300 bg-white p-1 shadow-md dark:border-neutral-700 dark:bg-neutral-800"
        >
            @if ($nullable)
                <button
                    type="button"
                    x-on:click="choose(null)"
                    class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-700"
                >
                    <span class="size-4 rounded-sm bg-neutral-200 dark:bg-neutral-500"></span>
                    <span>None</span>

                    <flux:spacer />

                    <flux:icon.check
                        x-show="value === null"
                        class="size-4 text-neutral-500 stroke-[2.5px]"
                    />
                </button>
            @endif

            <template x-for="option in options" :key="option.value">
                <button
                    type="button"
                    x-on:click="choose(option.value)"
                    class="flex w-full items-center gap-2 rounded-md px-2 py-2 text-left text-sm hover:bg-neutral-100 dark:hover:bg-neutral-700"
                >
                    <template x-if="option.swatch">
                        <span class="size-4 rounded-sm" :class="option.swatch"></span>
                    </template>

                    <template x-if="option.icon">
                        <span :class="option.color" x-html="option.icon"></span>
                    </template>

                    <span x-text="option.label"></span>

                    <flux:spacer />

                    <flux:icon.check
                        x-show="value === option.value"
                        class="size-4 text-neutral-500 stroke-[2.5px]"
                    />
                </button>
            </template>
        </div>
    </div>

    <flux:error :name="$model" />
</flux:field>
