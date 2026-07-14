<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Overwatch is lightweight project management for teams that want to ship faster.">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Overwatch — Plan less. Ship faster.">
    <meta property="og:description" content="Lightweight project management for teams that want to ship faster.">
    <meta property="og:image" content="{{ asset('images/overwatch-og.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Overwatch project board with the message: Plan less. Ship faster.">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Overwatch — Plan less. Ship faster.">
    <meta name="twitter:description" content="Lightweight project management for teams that want to ship faster.">
    <meta name="twitter:image" content="{{ asset('images/overwatch-og.png') }}">
    <meta name="twitter:image:alt" content="Overwatch project board with the message: Plan less. Ship faster.">

    <title>Overwatch — Plan less. Ship faster.</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    @fonts

    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen overflow-x-hidden bg-stone-50 font-sans text-neutral-950 antialiased selection:bg-violet-200 selection:text-violet-950">
    <div class="absolute inset-x-0 top-0 -z-10 h-176 overflow-hidden">
        <div class="absolute left-1/2 -top-72 h-136 w-280 -translate-x-1/2 rounded-full bg-violet-200/45 blur-3xl"></div>
        <div class="absolute -right-40 top-24 h-80 w-80 rounded-full bg-fuchsia-100/60 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-50 border-b border-neutral-200/70 bg-stone-50/85 backdrop-blur-xl">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-5 sm:px-8">
            <x-app-logo href="/" wire:navigate />

            <nav class="hidden items-center gap-7 text-sm text-neutral-600 md:flex">
                <a href="#product" class="transition hover:text-neutral-950">Product</a>
                <a href="#workflow" class="transition hover:text-neutral-950">Workflow</a>
                <a href="#features" class="transition hover:text-neutral-950">Features</a>
                <a href="#pricing" class="transition hover:text-neutral-950">Pricing</a>
            </nav>

            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="hidden rounded-lg px-3 py-2 text-sm font-medium text-neutral-600 transition hover:bg-white hover:text-neutral-950 sm:block">
                    Sign in
                </a>

                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-lg bg-neutral-950 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-neutral-800">
                    Start free

                    <svg viewBox="0 0 20 20" class="size-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M4 10h12M11 5l5 5-5 5"/>
                    </svg>
                </a>
            </div>
        </div>
    </header>

    <main>
        <section class="mx-auto max-w-7xl px-5 pb-16 pt-20 sm:px-8 sm:pb-24">
            <div class="mx-auto max-w-4xl text-center">
                <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-violet-200 bg-white/80 px-3 py-1.5 text-xs font-semibold text-violet-700 shadow-xs backdrop-blur">
                    <span class="relative flex size-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-violet-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full size-2 bg-violet-500"></span>
                    </span>

                    Built with Laravel
                </div>

                <h1 class="text-balance text-5xl font-semibold tracking-[-0.055em] text-neutral-950 sm:text-6xl lg:text-8xl">
                    Plan less.

                    <span class="block text-violet-600">Ship faster.</span>
                </h1>

                <p class="mx-auto mt-7 max-w-2xl text-pretty text-lg leading-8 text-neutral-600 sm:text-xl">
                    Overwatch keeps projects, tickets, and releases moving without burying your team in process. Everything you need to stay aligned, nothing you need to fight.
                </p>

                <div class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-violet-600 px-5 py-3 text-sm font-semibold text-white shadow-violet transition hover:bg-violet-500 sm:w-auto">
                        Start shipping for free
                        <svg viewBox="0 0 20 20" class="size-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M4 10h12M11 5l5 5-5 5"/>
                        </svg>
                    </a>
                    <a href="#product" class="inline-flex w-full items-center justify-center rounded-xl border border-neutral-200 bg-white px-5 py-3 text-sm font-semibold text-neutral-800 shadow-xs transition hover:border-neutral-300 hover:bg-neutral-50 sm:w-auto">
                        See how it works
                    </a>
                </div>

                <p class="mt-4 text-xs text-neutral-500">Free to use. No credit card required.</p>
            </div>

            <div id="product" class="relative mx-auto mt-16 max-w-7xl sm:mt-20">
                <div class="absolute inset-x-20 bottom-0 top-24 -z-10 rounded-[3rem] bg-violet-300/25 blur-3xl"></div>

                <div class="overflow-hidden rounded-xl border border-neutral-200 bg-neutral-100 sm:rounded-2xl">
                    <div class="flex h-10 items-center justify-between border-b border-neutral-200 bg-white px-4">
                        <div class="flex gap-1.5">
                            <span class="size-2.5 rounded-full bg-neutral-300"></span>
                            <span class="size-2.5 rounded-full bg-neutral-300"></span>
                            <span class="size-2.5 rounded-full bg-neutral-300"></span>
                        </div>

                        <div class="h-5 w-44 rounded-md bg-neutral-100"></div>

                        <div class="w-10"></div>
                    </div>

                    <div class="max-h-150 overflow-hidden sm:hidden">
                        <img
                            src="{{ asset('product-mobile.png') }}"
                            alt="Overwatch product board"
                            class="h-full w-full object-cover object-top"
                        />
                    </div>

                    <div class="max-h-200 overflow-hidden hidden sm:block">
                        <img
                            src="{{ asset('product-desktop.png') }}"
                            alt="Overwatch product board"
                            class="h-full w-full object-cover object-top"
                        />
                    </div>
                </div>

                <div class="absolute -bottom-6 left-6 rounded-2xl border border-neutral-200 bg-white p-4 shadow-soft">
                    <div class="flex items-center gap-3">
                        <span class="grid size-10 place-items-center rounded-xl bg-violet-100 text-violet-700">
                            <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M5 12.5 9.2 17 19 7"/>
                            </svg>
                        </span>

                        <div>
                            <p class="text-sm font-semibold">Work stays visible</p>
                            <p class="mt-0.5 text-xs text-neutral-500">From backlog to done.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-y border-neutral-200/80 bg-white/70">
            <div class="mx-auto grid max-w-7xl divide-y divide-neutral-200/80 px-5 sm:px-8 md:grid-cols-3 md:divide-x md:divide-y-0">
                <div class="py-8 md:pr-10">
                    <p class="text-sm font-semibold text-neutral-950">Fast by default</p>
                    <p class="mt-2 text-sm leading-6 text-neutral-600">A focused interface that keeps common actions close and unnecessary settings out of the way.</p>
                </div>
                <div class="py-8 md:px-10">
                    <p class="text-sm font-semibold text-neutral-950">Built around shipping</p>
                    <p class="mt-2 text-sm leading-6 text-neutral-600">Projects, tickets, releases, priorities, and status all live in one clean workflow.</p>
                </div>
                <div class="py-8 md:pl-10">
                    <p class="text-sm font-semibold text-neutral-950">Easy to understand</p>
                    <p class="mt-2 text-sm leading-6 text-neutral-600">Your team can open Overwatch and start working without learning a complicated system.</p>
                </div>
            </div>
        </section>

        <section id="workflow" class="mx-auto max-w-7xl px-5 py-24 sm:px-8 sm:py-32">
            <div class="grid items-start gap-14 lg:grid-cols-[0.85fr_1.15fr] lg:gap-20">
                <div class="lg:sticky lg:top-28">
                    <p class="text-sm font-semibold text-violet-600">One continuous workflow</p>
                    <h2 class="mt-4 text-4xl font-semibold tracking-[-0.04em] text-neutral-950 sm:text-5xl">
                        Built to stay out of your way.
                    </h2>
                    <p class="mt-6 max-w-xl text-lg leading-8 text-neutral-600">
                        Overwatch gives every project a clear place to live, every ticket a next step, and every release a finish line.
                    </p>
                </div>

                <div class="space-y-5">
                    <article class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-xs sm:p-8">
                        <div class="flex items-start gap-5">
                            <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-violet-100 text-violet-700">
                                <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M4 7h16M4 12h11M4 17h8"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-xl font-semibold tracking-tight">Capture the work</h3>
                                <p class="mt-2 leading-7 text-neutral-600">Create focused tickets with priorities, tags, due dates, assignees, and the context your team needs.</p>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-xs sm:p-8">
                        <div class="flex items-start gap-5">
                            <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-violet-100 text-violet-700">
                                <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M6 5h12v14H6zM9 9h6M9 13h4"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-xl font-semibold tracking-tight">Organize the release</h3>
                                <p class="mt-2 leading-7 text-neutral-600">Group related work into releases so scope, progress, and timing remain obvious from start to finish.</p>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-2xl border border-neutral-200 bg-white p-6 shadow-xs sm:p-8">
                        <div class="flex items-start gap-5">
                            <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-violet-100 text-violet-700">
                                <svg viewBox="0 0 24 24" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path d="M5 12.5 9.2 17 19 7"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-xl font-semibold tracking-tight">Move it forward</h3>
                                <p class="mt-2 leading-7 text-neutral-600">Use list or board views to see what is open, in progress, under review, and ready to ship.</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="features" class="border-y border-neutral-200 bg-neutral-950 text-white">
            <div class="mx-auto max-w-7xl px-5 py-24 sm:px-8 sm:py-32">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold text-violet-400">The essentials, done well</p>
                    <h2 class="mt-4 text-4xl font-semibold tracking-[-0.04em] sm:text-5xl">
                        Everything your team needs to keep moving.
                    </h2>
                    <p class="mt-6 text-lg leading-8 text-neutral-400">
                        Overwatch focuses on the parts of project management that make work clearer, not heavier.
                    </p>
                </div>

                <div class="mt-14 grid gap-px overflow-hidden rounded-2xl border border-neutral-800 bg-neutral-800 md:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-neutral-950 p-7">
                        <span class="grid size-10 place-items-center rounded-xl bg-violet-500/15 text-violet-300">01</span>
                        <h3 class="mt-8 text-lg font-semibold">Projects</h3>
                        <p class="mt-2 text-sm leading-6 text-neutral-400">Keep every initiative, member, ticket, tag, and release in a dedicated workspace.</p>
                    </div>
                    <div class="bg-neutral-950 p-7">
                        <span class="grid size-10 place-items-center rounded-xl bg-violet-500/15 text-violet-300">02</span>
                        <h3 class="mt-8 text-lg font-semibold">List and board views</h3>
                        <p class="mt-2 text-sm leading-6 text-neutral-400">Switch between a focused ticket list and a drag-and-drop status board.</p>
                    </div>
                    <div class="bg-neutral-950 p-7">
                        <span class="grid size-10 place-items-center rounded-xl bg-violet-500/15 text-violet-300">03</span>
                        <h3 class="mt-8 text-lg font-semibold">Releases</h3>
                        <p class="mt-2 text-sm leading-6 text-neutral-400">Define the next milestone and see exactly how close the team is to shipping it.</p>
                    </div>
                    <div class="bg-neutral-950 p-7">
                        <span class="grid size-10 place-items-center rounded-xl bg-violet-500/15 text-violet-300">04</span>
                        <h3 class="mt-8 text-lg font-semibold">Priorities and tags</h3>
                        <p class="mt-2 text-sm leading-6 text-neutral-400">Surface urgent work and add just enough structure to keep tickets easy to scan.</p>
                    </div>
                    <div class="bg-neutral-950 p-7">
                        <span class="grid size-10 place-items-center rounded-xl bg-violet-500/15 text-violet-300">05</span>
                        <h3 class="mt-8 text-lg font-semibold">Team collaboration</h3>
                        <p class="mt-2 text-sm leading-6 text-neutral-400">Invite collaborators, assign ownership, and keep everyone looking at the same plan.</p>
                    </div>
                    <div class="bg-neutral-950 p-7">
                        <span class="grid size-10 place-items-center rounded-xl bg-violet-500/15 text-violet-300">06</span>
                        <h3 class="mt-8 text-lg font-semibold">GitHub-ready</h3>
                        <p class="mt-2 text-sm leading-6 text-neutral-400">Connect engineering work to the tickets and branches where implementation happens.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="pricing" class="mx-auto max-w-7xl px-5 py-24 sm:px-8 sm:py-32">
            <div class="overflow-hidden rounded-3xl border border-violet-200 bg-violet-600 px-6 py-14 text-center text-white shadow-violet sm:px-12 sm:py-20">
                <p class="text-sm font-semibold text-violet-200">Simple from the start</p>
                <h2 class="mx-auto mt-4 max-w-3xl text-4xl font-semibold tracking-[-0.04em] sm:text-6xl">
                    Your next release deserves a clearer plan.
                </h2>
                <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-violet-100">
                    Create your workspace, add your projects, and start shipping. Overwatch is free to use.
                </p>
                <div class="mt-9">
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-violet-700 shadow-xs transition hover:bg-violet-50">
                        Get started free

                        <svg viewBox="0 0 20 20" class="size-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M4 10h12M11 5l5 5-5 5"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-neutral-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 p-5 sm:px-8 md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-3">
                <span class="grid size-8 place-items-center rounded-lg border border-neutral-200 bg-neutral-50">
                    <img src="{{ asset('logo.png') }}" alt="Overwatch Logo" class="p-0.5" />
                </span>
                <div>
                    <p class="text-sm font-semibold">Overwatch</p>
                    <p class="text-xs text-neutral-500">Built with Laravel.</p>
                </div>
            </div>

            <p class="text-xs text-neutral-500">© 2026 Overwatch. Plan less. Ship faster.</p>
        </div>
    </footer>
</body>
</html>
