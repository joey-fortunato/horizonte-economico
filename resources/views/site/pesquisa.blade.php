@extends('site.layouts.app')

@php
    $q = $q ?? '';
    $results = [
        ['section' => 'economia', 'title' => 'O que é a inflação e porque sobem os preços em Angola', 'excerpt' => 'A inflação mede a subida generalizada dos preços. Explicamos as causas, do câmbio ao custo dos combustíveis, e o efeito no seu bolso.', 'byline' => 'Ana Cardoso · 20 Set 2026 · 6 min'],
        ['section' => 'financas-pessoais', 'title' => 'Como proteger a poupança da inflação', 'excerpt' => 'Com a inflação a corroer o dinheiro parado, vale conhecer alternativas: depósitos a prazo, OT e outros instrumentos.', 'byline' => 'Marta Songo · 18 Set 2026 · 7 min'],
        ['section' => 'politica-economica', 'title' => 'BNA mantém taxa directora para conter a inflação', 'excerpt' => 'O comité de política monetária optou pela cautela. Percebemos o raciocínio e o que esperar nos próximos meses.', 'byline' => 'João Muanza · 15 Set 2026 · 5 min'],
    ];
    $term = $q !== '' ? $q : 'inflação';

    // realça o termo pesquisado no texto
    $mark = fn ($text) => $term === ''
        ? e($text)
        : preg_replace('/('.preg_quote($term, '/').')/iu', '<mark class="bg-[#faf0d4] text-ink">$1</mark>', e($text));
@endphp

@section('title', 'Pesquisa — Horizonte Económico')
@section('meta_description', 'Pesquise artigos, análises e explicações económicas no Horizonte Económico.')

@section('content')
    <section class="mx-auto w-full max-w-[1000px] px-10 pt-11">
        {{-- campo de pesquisa --}}
        <form action="{{ url('pesquisa') }}" method="get" class="flex items-center gap-[14px] border-[1.5px] border-ink px-5 py-4">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-green)" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="search" name="q" value="{{ $term }}" placeholder="Pesquisar artigos…" class="u-serif flex-1 bg-transparent text-[22px] text-ink placeholder:text-ink-3 focus:outline-none">
            <button type="submit" class="bg-green px-[22px] py-[11px] text-[14px] font-semibold text-white">Pesquisar</button>
        </form>

        <div class="u-serif mt-6 mb-[14px] text-[24px] font-semibold">48 resultados para <span class="text-green-link">“{{ $term }}”</span></div>
        <div class="flex flex-wrap gap-[9px] border-b-2 border-green pb-[18px]">
            <span class="cursor-pointer bg-green px-[14px] py-[7px] text-[12.5px] font-semibold text-white">Tudo</span>
            @foreach (['Economia', 'Finanças pessoais', 'Mercados', 'Literacia'] as $f)
                <span class="cursor-pointer border border-line-strong px-[14px] py-[7px] text-[12.5px] font-semibold text-ink-2">{{ $f }}</span>
            @endforeach
        </div>

        {{-- resultados --}}
        <div>
            @foreach ($results as $r)
                <article class="grid grid-cols-[1fr_200px] gap-6 border-b border-line py-6">
                    <div>
                        <x-site.category :slug="$r['section']" />
                        <h3 class="u-hed my-2 text-[25px]"><a href="{{ url('artigo/'.\Illuminate\Support\Str::slug($r['title'])) }}">{!! $mark($r['title']) !!}</a></h3>
                        <p class="u-serif mb-2 text-[16px] leading-[1.6] text-ink-2">{!! $mark($r['excerpt']) !!}</p>
                        <div class="u-byline">{{ $r['byline'] }}</div>
                    </div>
                    <x-site.thumb ratio="3/2" class="self-start" />
                </article>
            @endforeach
        </div>

        <div class="my-8 flex justify-center gap-2">
            <span class="bg-green px-4 py-2 text-[13px] font-semibold text-white">1</span>
            <span class="cursor-pointer border border-line-strong px-4 py-2 text-[13px] font-semibold text-ink-2">2</span>
            <span class="cursor-pointer border border-line-strong px-4 py-2 text-[13px] font-semibold text-ink-2">3</span>
            <span class="cursor-pointer border border-line-strong px-4 py-2 text-[13px] font-semibold text-ink-2">Seguinte ›</span>
        </div>

        {{-- estado vazio (referência) --}}
        <div class="mb-14 border border-line bg-panel p-10 text-center">
            <div class="u-serif text-[24px] font-semibold">Sem resultados</div>
            <p class="u-serif mx-auto mt-2 mb-4 max-w-[460px] text-[16px] leading-[1.6] text-ink-2">Não encontrámos artigos para a sua pesquisa. Verifique a ortografia ou explore uma das secções.</p>
            <div class="flex flex-wrap justify-center gap-[9px]">
                @foreach (['economia', 'mercados', 'literacia-financeira'] as $s)
                    <a href="{{ url('categoria/'.$s) }}" class="border border-line-strong bg-white px-[14px] py-[7px] text-[12.5px] font-semibold text-ink-2">{{ config('sections.'.$s.'.label') }}</a>
                @endforeach
            </div>
        </div>
    </section>
@endsection
