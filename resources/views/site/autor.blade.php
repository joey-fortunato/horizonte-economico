@extends('site.layouts.app')

@php
    $author = [
        'name' => 'João Muanza',
        'role' => 'Editor de Economia',
        'bio' => 'Economista e jornalista. Escreve sobre política económica, contas públicas e o impacto das decisões macro na vida das pessoas há mais de dez anos. Licenciado em Economia pela UAN.',
        'count' => 86,
    ];
    $articles = [
        ['section' => 'politica-economica', 'title' => 'OGE 2027: onde vai o dinheiro do Estado', 'excerpt' => 'Prioridades orçamentais e o impacto directo no rendimento das famílias.', 'byline' => '28 Set · 8 min'],
        ['section' => 'mercados', 'title' => 'Procura por Obrigações do Tesouro sobe no leilão', 'excerpt' => 'Investidores institucionais preferem prazos mais longos face à inflação.', 'byline' => '20 Set · 5 min'],
        ['section' => 'economia', 'title' => 'Reservas internacionais: onde estamos em 2026', 'excerpt' => 'Uma leitura do colchão cambial do país e do que o sustenta.', 'byline' => '22 Set · 4 min'],
        ['section' => 'politica-economica', 'title' => 'Dívida pública: perguntas frequentes', 'excerpt' => 'Respondemos às dúvidas mais comuns sobre o endividamento do Estado.', 'byline' => '14 Set · 5 min'],
    ];
@endphp

@section('title', $author['name'].' — Horizonte Económico')
@section('meta_description', $author['role'].' no Horizonte Económico. '.$author['bio'])

@section('content')
    {{-- cabeçalho do autor (banda institucional verde) --}}
    <section class="bg-green text-white">
        <div class="u-wrap flex items-center gap-8 py-11">
            <div class="u-photo h-[118px] w-[118px] shrink-0 rounded-full" style="background:#0e2c24;border-color:var(--color-green-2)"></div>
            <div class="flex-1">
                <div class="u-byline font-bold uppercase tracking-[0.1em]" style="color:var(--color-gold-soft)">{{ $author['role'] }}</div>
                <h1 class="u-serif my-[10px] text-[42px] font-bold text-white">{{ $author['name'] }}</h1>
                <p class="u-serif max-w-[620px] text-[17px] leading-[1.6] text-[#cfe0d8]">{{ $author['bio'] }}</p>
                <div class="mt-4 flex items-center gap-[10px]">
                    @foreach (['linkedin', 'x', 'email'] as $net)
                        <span class="flex h-[38px] w-[38px] items-center justify-center border border-[rgba(255,255,255,0.3)] text-[#eaf1ed]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
                        </span>
                    @endforeach
                    <span class="u-byline ml-[10px]" style="color:#8fb0a4">{{ $author['count'] }} artigos publicados</span>
                </div>
            </div>
        </div>
    </section>

    {{-- artigos do autor --}}
    <section class="u-wrap pt-9">
        <div class="mb-6 flex items-baseline border-b-2 border-green pb-[10px]"><h2 class="u-hed text-[22px]">Artigos de {{ $author['name'] }}</h2></div>
        <div>
            @foreach ($articles as $a)
                <article class="grid grid-cols-[1fr_200px] gap-6 border-b border-line py-6">
                    <div>
                        <x-site.category :slug="$a['section']" />
                        <h3 class="u-hed my-2 text-[26px]"><a href="#">{{ $a['title'] }}</a></h3>
                        <p class="u-serif mb-2 text-[16px] leading-[1.55] text-ink-2">{{ $a['excerpt'] }}</p>
                        <div class="u-byline">{{ $a['byline'] }}</div>
                    </div>
                    <x-site.thumb ratio="3/2" class="self-start" />
                </article>
            @endforeach
        </div>
        <div class="my-14 flex justify-center">
            <a href="#" class="border-b border-green-link pb-[3px] text-[13px] font-bold uppercase tracking-[0.08em] text-green-link">Carregar mais</a>
        </div>
    </section>
@endsection
