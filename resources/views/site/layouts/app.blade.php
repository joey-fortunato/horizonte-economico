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
    {{-- Open Graph --}}
    <meta property="og:site_name" content="Horizonte Económico">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('title', 'Horizonte Económico')">
    <meta property="og:description" content="@yield('meta_description', 'Informação e análise económica.')">
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
