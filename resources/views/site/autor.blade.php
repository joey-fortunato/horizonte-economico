@extends('site.layouts.app')

@section('title', $author->name.' — Horizonte Económico')
@section('meta_description', $author->title.' no Horizonte Económico. '.$author->bio)

@section('content')
    {{-- cabeçalho do autor (banda institucional verde) --}}
    <section class="bg-green text-white">
        <div class="u-wrap flex items-center gap-8 py-11">
            <div class="u-photo h-[118px] w-[118px] shrink-0 rounded-full" style="background:#0e2c24;border-color:var(--color-green-2)"></div>
            <div class="flex-1">
                <div class="u-byline font-bold uppercase tracking-[0.1em]" style="color:var(--color-gold-soft)">{{ $author->title }}</div>
                <h1 class="u-serif my-[10px] text-[42px] font-bold text-white">{{ $author->name }}</h1>
                <p class="u-serif max-w-[620px] text-[17px] leading-[1.6] text-[#cfe0d8]">{{ $author->bio }}</p>
                <div class="mt-4 flex items-center gap-[10px]">
                    @foreach (['linkedin', 'x', 'email'] as $net)
                        <span class="flex h-[38px] w-[38px] items-center justify-center border border-[rgba(255,255,255,0.3)] text-[#eaf1ed]">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="4" width="16" height="16" rx="2"/></svg>
                        </span>
                    @endforeach
                    <span class="u-byline ml-[10px]" style="color:#8fb0a4">{{ $total }} {{ \Illuminate\Support\Str::plural('artigo publicado', $total) }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- artigos do autor --}}
    <section class="u-wrap pt-9">
        <div class="mb-6 flex items-baseline border-b-2 border-green pb-[10px]"><h2 class="u-hed text-[22px]">Artigos de {{ $author->name }}</h2></div>

        @if ($articles->isEmpty())
            <p class="u-serif py-10 text-center text-[18px] text-ink-2">Este autor ainda não tem artigos publicados.</p>
        @else
        <div>
            @foreach ($articles as $a)
                <article class="grid grid-cols-[1fr_200px] gap-6 border-b border-line py-6">
                    <div>
                        <x-site.category :category="$a->category" />
                        <h3 class="u-hed my-2 text-[26px]"><a href="{{ route('artigo', $a->slug) }}">{{ $a->title }}</a></h3>
                        <p class="u-serif mb-2 text-[16px] leading-[1.55] text-ink-2">{{ $a->excerpt }}</p>
                        <div class="u-byline">{{ $a->published_at->translatedFormat('j M Y') }} · {{ $a->reading_minutes }} min</div>
                    </div>
                    <a href="{{ route('artigo', $a->slug) }}" class="self-start"><x-site.thumb ratio="3/2" :media="$a->cover" /></a>
                </article>
            @endforeach
        </div>
        <div class="my-12">{{ $articles->onEachSide(1)->links() }}</div>
        @endif
    </section>
@endsection
