<!doctype html>
<html lang="pt" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Horizonte Económico')</title>
    <meta name="description" content="@yield('meta_description', 'Informação e análise económica para decisões mais informadas. Compreender a economia para viver melhor.')">
    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @endif
    {{-- Ícones / manifest (kit de marca) --}}
    <link rel="icon" href="/favicon.ico" sizes="48x48">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon-180.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="theme-color" content="#123B30">
    {{-- Open Graph --}}
    <meta property="og:site_name" content="Horizonte Económico">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'Horizonte Económico')">
    <meta property="og:description" content="@yield('meta_description', 'Informação e análise económica.')">
    <meta property="og:image" content="@yield('og_image', url('/imagem-partilha-1200x630.png'))">
    <meta name="twitter:card" content="summary_large_image">
    @stack('head')
    @vite('resources/css/site.css')
</head>
<body class="bg-paper text-ink">
    @include('site.partials.header')

    <main>
        @yield('content')
    </main>

    @include('site.partials.footer')
    @stack('scripts')
</body>
</html>
