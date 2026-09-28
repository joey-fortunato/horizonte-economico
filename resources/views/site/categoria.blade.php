@extends('site.layouts.app')

@php
    $sec = config('sections.'.$slug);
    $color = $sec['color'];

    $filters = ['Todos', 'Câmbio', 'Petróleo', 'Dívida', 'Acções', 'Commodities'];

    $featured = [
        'title' => 'Kwanza recupera face ao dólar após leilão cambial do BNA',
        'standfirst' => 'O Banco Nacional injectou divisas no mercado e a taxa oficial aproximou-se do câmbio informal pela primeira vez em meses.',
        'byline' => 'Ana Cardoso · 28 Set 2026 · 6 min',
    ];

    $articles = [
        ['title' => 'Brent acima dos 78 dólares pressiona as receitas do Estado', 'standfirst' => 'A subida do crude alivia as contas externas mas mantém a dependência.', 'byline' => 'João Muanza · 27 Set · 5 min'],
        ['title' => 'Procura por Obrigações do Tesouro sobe no leilão', 'standfirst' => 'Investidores institucionais preferem prazos mais longos face à inflação.', 'byline' => 'Ana Cardoso · 26 Set · 5 min'],
        ['title' => 'Câmbio informal vs. oficial: porquê a diferença', 'standfirst' => 'Explicámos o que sustenta o spread entre as duas taxas.', 'byline' => 'Pedro Lemos · 25 Set · 7 min'],
        ['title' => 'Diamantes e cobre: a aposta na diversificação', 'standfirst' => 'Minerais críticos ganham peso na estratégia de exportação.', 'byline' => 'Bruno Kiala · 24 Set · 6 min'],
        ['title' => 'BODIVA: o que muda com as novas cotadas', 'standfirst' => 'A bolsa angolana prepara-se para receber mais emissões.', 'byline' => 'Ana Cardoso · 23 Set · 5 min'],
        ['title' => 'Reservas internacionais: onde estamos em 2026', 'standfirst' => 'Uma leitura do colchão cambial do país e do que o sustenta.', 'byline' => 'João Muanza · 22 Set · 4 min'],
    ];
@endphp

@section('title', $sec['label'].' — Horizonte Económico')
@section('meta_description', $sec['label'].': '.$sec['desc'].'. Notícias e análise do Horizonte Económico.')

@section('content')
    {{-- ===== Masthead da categoria (identidade pela cor da secção) ===== --}}
    <section class="u-wrap pt-10">
        <div class="u-byline">Início <span class="text-line-strong">/</span> Categoria</div>
        <div class="mt-3 flex items-center gap-4">
            <span class="inline-block h-8 w-8 shrink-0" style="background: {{ $color }};"></span>
            <h1 class="u-hed text-[52px] leading-[1.02]">{{ $sec['label'] }}</h1>
        </div>
        <p class="u-serif mt-3 max-w-[640px] text-[19px] leading-[1.55] text-ink-2">{{ $sec['desc'] }}. Notícias, análise e contexto sobre este tema, com o rigor do Horizonte Económico.</p>
        <div class="u-byline mt-4">142 artigos · Actualizado há 2 horas</div>
        <div class="mt-6 h-[2px] w-full" style="background: {{ $color }};"></div>
    </section>

    {{-- ===== Filtros ===== --}}
    <section class="u-wrap pt-6">
        <div class="flex flex-wrap items-center gap-[10px] border-b border-line pb-6">
            @foreach ($filters as $i => $f)
                <span class="cursor-pointer px-4 py-2 text-[13px] font-semibold {{ $i === 0 ? 'text-white' : 'border border-line-strong text-ink-2' }}"
                      @if ($i === 0) style="background: {{ $color }};" @endif>{{ $f }}</span>
            @endforeach
            <span class="u-byline ml-auto">Ordenar: <b class="text-ink">Mais recentes</b></span>
        </div>
    </section>

    {{-- ===== Destaque da categoria ===== --}}
    <section class="u-wrap pt-8">
        <div class="grid grid-cols-[1.15fr_1fr] gap-10 border-b border-line pb-9">
            <div class="flex flex-col justify-center">
                <x-site.category :slug="$slug" variant="lead" />
                <h2 class="u-hed mb-[14px] mt-3 text-[38px] leading-[1.08]"><a href="{{ url('artigo/kwanza-dolar') }}">{{ $featured['title'] }}</a></h2>
                <p class="u-serif mb-4 text-[18px] leading-[1.6] text-ink-2">{{ $featured['standfirst'] }}</p>
                <div class="u-byline">{{ $featured['byline'] }}</div>
            </div>
            <x-site.thumb ratio="3/2" class="self-stretch" />
        </div>
    </section>

    {{-- ===== River / grelha ===== --}}
    <section class="u-wrap pt-9">
        <div class="grid grid-cols-3 gap-x-9 gap-y-9">
            @foreach ($articles as $a)
                <article>
                    <x-site.thumb ratio="16/9" class="mb-[14px]" />
                    <x-site.category :slug="$slug" />
                    <h3 class="u-hed my-2 text-[22px]"><a href="#">{{ $a['title'] }}</a></h3>
                    <p class="u-serif mb-[10px] text-[15px] leading-[1.5] text-ink-2">{{ $a['standfirst'] }}</p>
                    <div class="u-byline">{{ $a['byline'] }}</div>
                </article>
            @endforeach
        </div>

        {{-- ===== Paginação ===== --}}
        <div class="my-14 flex items-center justify-center gap-2">
            <span class="cursor-pointer border border-line-strong px-4 py-2 text-[13px] font-semibold text-ink-2">‹ Anterior</span>
            <span class="px-4 py-2 text-[13px] font-semibold text-white" style="background: {{ $color }};">1</span>
            <span class="cursor-pointer border border-line-strong px-4 py-2 text-[13px] font-semibold text-ink-2">2</span>
            <span class="cursor-pointer border border-line-strong px-4 py-2 text-[13px] font-semibold text-ink-2">3</span>
            <span class="u-byline">…</span>
            <span class="cursor-pointer border border-line-strong px-4 py-2 text-[13px] font-semibold text-ink-2">12</span>
            <span class="cursor-pointer border border-line-strong px-4 py-2 text-[13px] font-semibold text-ink-2">Seguinte ›</span>
        </div>
    </section>
@endsection
