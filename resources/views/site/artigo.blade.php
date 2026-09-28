@extends('site.layouts.app')

@php $sec = $article->category; @endphp

@section('title', ($article->seo_title ?: $article->title).' — Horizonte Económico')
@section('meta_description', $article->seo_description ?: $article->excerpt)
@section('og_type', 'article')
@section('canonical', route('artigo', $article->slug))
@section('og_image', $article->cover?->url() ?? url('/imagem-partilha-1200x630.png'))

@push('head')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'NewsArticle',
        'headline' => $article->title,
        'description' => $article->excerpt,
        'datePublished' => $article->published_at?->toAtomString(),
        'dateModified' => $article->updated_at?->toAtomString(),
        'articleSection' => $sec?->name,
        'image' => [$article->cover?->url() ?? url('/imagem-partilha-1200x630.png')],
        'mainEntityOfPage' => route('artigo', $article->slug),
        'author' => ['@type' => 'Person', 'name' => $article->author->name, 'url' => route('autor', $article->author->slug)],
        'publisher' => ['@type' => 'Organization', 'name' => 'Horizonte Económico', 'logo' => ['@type' => 'ImageObject', 'url' => url('/brand/he-horizontal-cor.svg')]],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array_values(array_filter([
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Início', 'item' => url('/')],
            $sec ? ['@type' => 'ListItem', 'position' => 2, 'name' => $sec->name, 'item' => route('categoria', $sec->slug)] : null,
            ['@type' => 'ListItem', 'position' => $sec ? 3 : 2, 'name' => $article->title, 'item' => route('artigo', $article->slug)],
        ])),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

@section('content')
    {{-- ===== Barra de contexto + ferramentas de leitura (fixa ao rolar) ===== --}}
    <div class="sticky top-0 z-50">
        <div class="text-[#cfe0d8]" style="background:#0e3228;">
            <div class="u-wrap flex h-11 items-center text-[13px]">
                @if ($sec)
                    <a href="{{ route('categoria', $sec->slug) }}" class="flex items-center gap-2 font-semibold text-white">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $sec->color }}" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Voltar a {{ $sec->name }}
                    </a>
                @endif
                <div class="ml-auto flex items-center gap-2">
                    <span class="mr-1 text-[#8fb0a4]">Ferramentas de leitura</span>
                    <span class="flex items-center gap-[7px] border border-green-2 px-[11px] py-[6px] text-[#eaf1ed]"><b class="text-[11px]">A</b><b class="text-[15px]">A</b></span>
                    <span class="flex items-center gap-[7px] border border-green-2 px-[11px] py-[6px] text-[#eaf1ed]"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M6 4h12v16l-6-4-6 4z"/></svg>Guardar</span>
                    <span class="flex items-center gap-[7px] border border-green-2 px-[11px] py-[6px] text-[#eaf1ed]"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M11 5 6 9H2v6h4l5 4z"/><path d="M15.5 8.5a5 5 0 0 1 0 7"/></svg>Ouvir</span>
                    <span class="flex items-center gap-[7px] border border-green-2 px-[11px] py-[6px] text-[#eaf1ed]"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/></svg>Partilhar</span>
                </div>
            </div>
        </div>
        <div class="h-[3px] bg-line"><div class="h-full w-[38%] bg-gold"></div></div>
    </div>

    {{-- ===== Cabeçalho do artigo ===== --}}
    <div class="u-wrap pt-9">
        <div class="mx-auto max-w-[760px]">
            @if ($sec)
                <div class="u-byline flex gap-2">Início <span class="text-line-strong">/</span> <a href="{{ route('categoria', $sec->slug) }}" class="font-semibold" style="color:{{ $sec->color }}">{{ $sec->name }}</a></div>
                <div class="my-4"><x-site.category :category="$sec" variant="lead" /></div>
            @endif
            <h1 class="u-hed mb-[18px] text-[48px] leading-[1.08]">{{ $article->title }}</h1>
            <p class="u-serif mb-6 text-[23px] leading-[1.5] text-ink-2">{{ $article->excerpt }}</p>
            <div class="flex flex-wrap items-center gap-[14px] border-y border-line py-4">
                <div class="u-byline text-[14px]">Por <b class="font-semibold text-ink">{{ $article->author->name }}</b>, {{ $article->author->title }}</div>
                <span class="text-line-strong">·</span><span class="u-byline">{{ $article->published_at->translatedFormat('j \d\e F \d\e Y') }}</span>
                <span class="u-byline ml-auto">{{ $article->reading_minutes }} min de leitura</span>
            </div>
        </div>

        {{-- capa --}}
        <div class="mx-auto mt-8 max-w-[1000px]">
            <x-site.thumb ratio="16/9" :media="$article->cover" :caption="$article->cover?->alt_text" />
        </div>

        {{-- ===== Corpo ===== --}}
        <div class="mt-11 grid grid-cols-[64px_minmax(0,720px)_300px] justify-center gap-12">
            {{-- rail partilha --}}
            <div class="sticky top-16 flex flex-col gap-[10px] self-start">
                @foreach (['whatsapp','facebook','linkedin','x'] as $net)
                    <span class="flex h-[42px] w-[42px] items-center justify-center border border-line-strong text-ink">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                    </span>
                @endforeach
            </div>

            {{-- prosa --}}
            <div>
                <div class="u-prose">{!! $article->body !!}</div>

                {{-- nota de correção --}}
                @if ($article->correction_note)
                    <div class="mt-8 border-l-[3px] border-gold p-[14px_18px]" style="background:#faf3e2;font-family:var(--font-sans)">
                        <div class="mb-1 text-[11px] font-bold uppercase tracking-[0.08em] text-gold">Nota de correcção</div>
                        <p class="text-[13.5px] leading-[1.6] text-ink-2">{{ $article->correction_note }}</p>
                    </div>
                @endif

                {{-- fontes --}}
                @if (!empty($article->sources))
                    <div id="fontes" class="mt-7 border border-line bg-panel p-6" style="font-family:var(--font-sans)">
                        <div class="border-b-2 border-green pb-3 text-[12px] font-bold uppercase tracking-[0.08em] text-ink">Fontes e referências</div>
                        <ol class="mt-[14px] flex list-decimal flex-col gap-[11px] pl-[22px]">
                            @foreach ($article->sources as $s)
                                <li class="text-[14px] leading-[1.55] text-ink-2">{{ $s['title'] ?? '' }} — <b class="font-semibold text-ink">{{ $s['org'] ?? '' }}</b> @if(!empty($s['url']))· <a href="{{ $s['url'] }}" class="font-semibold text-green-link">ver fonte ↗</a>@endif</li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                {{-- etiquetas --}}
                @if ($article->tags->isNotEmpty())
                    <div class="mt-8 flex flex-wrap gap-[10px] border-t border-line pt-[22px]" style="font-family:var(--font-sans)">
                        @foreach ($article->tags as $t)
                            <a href="#" class="border border-line-strong px-[13px] py-[6px] text-[12px] font-semibold text-ink-2">{{ $t->name }}</a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- aside --}}
            <aside class="sticky top-16 self-start">
                <div class="border-b-2 border-green pb-[10px] text-[11px] font-bold uppercase tracking-[0.1em] text-ink-3">Mais deste autor</div>
                <div class="mt-3 bg-green p-[22px_20px] text-[#eaf1ed]">
                    <div class="u-serif text-[20px] font-semibold leading-[1.2] text-white">Não perca a análise da semana</div>
                    <input type="email" placeholder="o.seu@email.co.ao" class="my-[14px] w-full bg-white px-[13px] py-[10px] text-[13px] text-ink-3 focus:outline-none">
                    <button class="w-full bg-gold-soft py-[11px] text-[13.5px] font-bold text-green">Subscrever</button>
                </div>
            </aside>
        </div>
    </div>

    {{-- caixa de autor --}}
    <div class="mt-14 border-y border-line bg-panel">
        <div class="u-wrap py-9">
            <div class="mx-auto flex max-w-[1000px] items-start gap-[22px]">
                <div class="u-photo h-[76px] w-[76px] shrink-0 rounded-full bg-white"></div>
                <div class="max-w-[640px]">
                    <div class="u-byline font-bold uppercase tracking-[0.1em] text-gold">Sobre o autor</div>
                    <div class="u-hed my-[6px] text-[22px]">{{ $article->author->name }}</div>
                    <p class="text-[14.5px] leading-[1.65] text-ink-2" style="font-family:var(--font-sans)">{{ $article->author->bio }} <a href="{{ route('autor', $article->author->slug) }}" class="font-semibold text-green-link">Ver todos os artigos →</a></p>
                </div>
            </div>
        </div>
    </div>

    {{-- relacionadas --}}
    @if ($related->isNotEmpty())
    <div class="u-wrap" style="padding-top:52px">
        <div class="mx-auto max-w-[1000px]">
            <div class="mb-6 flex items-baseline border-b-2 border-green pb-[10px]"><h2 class="u-hed text-[23px]">Continue a ler</h2></div>
            <div class="grid grid-cols-3 gap-7">
                @foreach ($related as $r)
                    <article>
                        <a href="{{ route('artigo', $r->slug) }}"><x-site.thumb ratio="3/2" :media="$r->cover" class="mb-[14px]" /></a>
                        <x-site.category :category="$r->category" />
                        <h3 class="u-hed my-2 text-[20px]"><a href="{{ route('artigo', $r->slug) }}">{{ $r->title }}</a></h3>
                        <div class="u-byline">{{ $r->reading_minutes }} min de leitura</div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@endsection
