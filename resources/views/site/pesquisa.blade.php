@extends('site.layouts.app')

@php
    $term = $q ?? '';
    $mark = function ($text) use ($term) {
        $text = e($text);
        if ($term === '') {
            return $text;
        }
        return preg_replace('/('.preg_quote($term, '/').')/iu', '<mark class="bg-[#faf0d4] text-ink">$1</mark>', $text);
    };
@endphp

@section('title', ($term !== '' ? 'Pesquisa: '.$term : 'Pesquisa').' — Horizonte Económico')
@section('meta_description', 'Pesquise artigos, análises e explicações económicas no Horizonte Económico.')
@section('robots', 'noindex, follow')

@section('content')
    <section class="mx-auto w-full max-w-[1000px] px-10 pt-11">
        <form action="{{ route('pesquisa') }}" method="get" class="flex items-center gap-[14px] border-[1.5px] border-ink px-5 py-4">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-green)" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            <input type="search" name="q" value="{{ $term }}" placeholder="Pesquisar artigos…" class="u-serif flex-1 bg-transparent text-[22px] text-ink placeholder:text-ink-3 focus:outline-none">
            <button type="submit" class="bg-green px-[22px] py-[11px] text-[14px] font-semibold text-white">Pesquisar</button>
        </form>

        @if ($term !== '')
            <div class="u-serif mt-6 mb-[14px] text-[24px] font-semibold">{{ $results->total() }} {{ \Illuminate\Support\Str::plural('resultado', $results->total()) }} para <span class="text-green-link">“{{ $term }}”</span></div>
        @else
            <div class="u-serif mt-6 mb-[14px] text-[24px] font-semibold">Artigos recentes</div>
        @endif
        <div class="border-b-2 border-green pb-[18px]"></div>

        @if ($results->isEmpty())
            <div class="my-14 border border-line bg-panel p-10 text-center">
                <div class="u-serif text-[24px] font-semibold">Sem resultados</div>
                <p class="u-serif mx-auto mt-2 mb-4 max-w-[460px] text-[16px] leading-[1.6] text-ink-2">Não encontrámos artigos para “{{ $term }}”. Verifique a ortografia ou explore uma das secções.</p>
                <div class="flex flex-wrap justify-center gap-[9px]">
                    @foreach (\App\Models\Category::active()->orderBy('position')->take(4)->get() as $c)
                        <a href="{{ route('categoria', $c->slug) }}" class="border border-line-strong bg-white px-[14px] py-[7px] text-[12.5px] font-semibold text-ink-2">{{ $c->name }}</a>
                    @endforeach
                </div>
            </div>
        @else
            <div>
                @foreach ($results as $r)
                    <article class="grid grid-cols-[1fr_200px] gap-6 border-b border-line py-6">
                        <div>
                            <x-site.category :category="$r->category" />
                            <h3 class="u-hed my-2 text-[25px]"><a href="{{ route('artigo', $r->slug) }}">{!! $mark($r->title) !!}</a></h3>
                            <p class="u-serif mb-2 text-[16px] leading-[1.6] text-ink-2">{!! $mark(\Illuminate\Support\Str::limit($r->excerpt, 180)) !!}</p>
                            <div class="u-byline">{{ $r->author->name }} · {{ $r->published_at->translatedFormat('j M Y') }} · {{ $r->reading_minutes }} min</div>
                        </div>
                        <a href="{{ route('artigo', $r->slug) }}" class="self-start"><x-site.thumb ratio="3/2" :media="$r->cover" /></a>
                    </article>
                @endforeach
            </div>
            <div class="my-8">{{ $results->onEachSide(1)->links() }}</div>
        @endif
    </section>
@endsection
