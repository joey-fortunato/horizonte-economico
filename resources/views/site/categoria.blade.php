@extends('site.layouts.app')

@php $color = $category->color; @endphp

@section('title', $category->name.' — Horizonte Económico')
@section('meta_description', $category->name.': '.$category->description.'. Notícias e análise do Horizonte Económico.')
@section('canonical', route('categoria', $category->slug))

@section('content')
    {{-- ===== Masthead da categoria ===== --}}
    <section class="u-wrap pt-10">
        <div class="u-byline">Início <span class="text-line-strong">/</span> Categoria</div>
        <div class="mt-3 flex items-center gap-4">
            <span class="inline-block h-8 w-8 shrink-0" style="background: {{ $color }};"></span>
            <h1 class="u-hed text-[52px] leading-[1.02]">{{ $category->name }}</h1>
        </div>
        <p class="u-serif mt-3 max-w-[640px] text-[19px] leading-[1.55] text-ink-2">{{ $category->description }}. Notícias, análise e contexto sobre este tema, com o rigor do Horizonte Económico.</p>
        <div class="u-byline mt-4">{{ $total }} {{ \Illuminate\Support\Str::plural('artigo', $total) }}</div>
        <div class="mt-6 h-[2px] w-full" style="background: {{ $color }};"></div>
    </section>

    @if ($featured)
    {{-- ===== Destaque ===== --}}
    <section class="u-wrap pt-8">
        <div class="grid grid-cols-[1.15fr_1fr] gap-10 border-b border-line pb-9">
            <div class="flex flex-col justify-center">
                <x-site.category :category="$category" variant="lead" />
                <h2 class="u-hed mb-[14px] mt-3 text-[38px] leading-[1.08]"><a href="{{ route('artigo', $featured->slug) }}">{{ $featured->title }}</a></h2>
                <p class="u-serif mb-4 text-[18px] leading-[1.6] text-ink-2">{{ $featured->excerpt }}</p>
                <div class="u-byline">{{ $featured->author->name }} · {{ $featured->published_at->translatedFormat('j M Y') }} · {{ $featured->reading_minutes }} min</div>
            </div>
            <a href="{{ route('artigo', $featured->slug) }}" class="self-stretch"><x-site.thumb ratio="3/2" :media="$featured->cover" class="h-full" /></a>
        </div>
    </section>
    @endif

    {{-- ===== Grelha ===== --}}
    <section class="u-wrap pt-9">
        @if ($articles->isEmpty())
            <p class="u-serif py-10 text-center text-[18px] text-ink-2">Ainda não há mais artigos nesta secção.</p>
        @else
        <div class="grid grid-cols-3 gap-x-9 gap-y-9">
            @foreach ($articles as $a)
                <article>
                    <a href="{{ route('artigo', $a->slug) }}"><x-site.thumb ratio="16/9" :media="$a->cover" class="mb-[14px]" /></a>
                    <x-site.category :category="$category" />
                    <h3 class="u-hed my-2 text-[22px]"><a href="{{ route('artigo', $a->slug) }}">{{ $a->title }}</a></h3>
                    <p class="u-serif mb-[10px] text-[15px] leading-[1.5] text-ink-2">{{ $a->excerpt }}</p>
                    <div class="u-byline">{{ $a->author->name }} · {{ $a->published_at->translatedFormat('j M') }} · {{ $a->reading_minutes }} min</div>
                </article>
            @endforeach
        </div>
        @endif

        {{-- ===== Paginação ===== --}}
        <div class="my-14 [&_svg]:inline">
            {{ $articles->onEachSide(1)->links() }}
        </div>
    </section>
@endsection
