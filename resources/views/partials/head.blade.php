<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

@php
    $metaTitle = filled($title ?? null) ? $title.' - '.config('app.name', 'Overwatch') : config('app.name', 'Overwatch');
    $metaDescription = 'Lightweight project management for teams that want to ship faster.';
@endphp

<meta name="description" content="{{ $metaDescription }}">

<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ asset('images/overwatch-og.png') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Overwatch ticket board with the message: Plan less. Ship faster.">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ asset('images/overwatch-twitter.png') }}">
<meta name="twitter:image:alt" content="Overwatch ticket board showing search, sorting, filters, and drag-and-drop workflows.">

<title>
    {{ $metaTitle }}
</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

@fonts

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
@livewireStyles
