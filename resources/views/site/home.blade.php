@extends('site.layouts.app')

@section('title', 'Horizonte Económico — Economia, análise e literacia financeira')
@section('meta_description', 'Notícias, análise e explicações económicas focadas em Angola. Compreender a economia para tomar melhores decisões.')

@section('content')
    @if ($lead)
    {{-- ===== LEAD ===== --}}
    <section class="u-wrap pt-9">
        <div class="grid grid-cols-[1fr_1px_384px] gap-[38px]">
            <article>
                <div class="mb-4"><x-site.category :category="$lead->category" variant="lead" /></div>
                <h1 class="u-hed mb-[18px] text-[54px] leading-[1.04]">
                    <a href="{{ route('artigo', $lead->slug) }}">{{ $lead->title }}</a>
                </h1>
                <p class="u-serif mb-5 max-w-[680px] text-[22px] leading-[1.5] text-ink-2">{{ $lead->excerpt }}</p>
                <div class="u-byline mb-5 flex flex-wrap items-center gap-[10px]">
                    Por <b class="font-semibold text-ink">{{ $lead->author->name }}</b>, {{ $lead->author->title }} · {{ $lead->reading_minutes }} min de leitura
                    <span class="text-line-strong">·</span>
                    <span class="flex items-center gap-[5px] font-semibold text-gold">
                        <span class="inline-block h-[6px] w-[6px] rounded-full bg-gold"></span>Actualizado {{ $lead->updated_at->diffForHumans() }}
                    </span>
                </div>
                <a href="{{ route('artigo', $lead->slug) }}"><x-site.thumb ratio="3/2" :media="$lead->cover" /></a>
            </article>

            <div class="bg-line"></div>

            <div class="flex flex-col">
                @foreach ($secondary as $i => $s)
                    @if ($i > 0)<hr class="u-rule">@endif
                    <article class="{{ $i === 0 ? 'pb-5' : 'py-5' }}">
                        <x-site.thumb ratio="16/9" :media="$s->cover" class="mb-3" />
                        <x-site.category :category="$s->category" />
                        <h2 class="u-hed my-2 text-[22px]"><a href="{{ route('artigo', $s->slug) }}">{{ $s->title }}</a></h2>
                        <div class="u-byline">{{ $s->author->name }} · {{ $s->published_at->diffForHumans() }}</div>
                    </article>
                @endforeach
            </div>
        </div>
        <hr class="u-rule mt-11">
    </section>

    {{-- ===== ÚLTIMAS + MAIS LIDOS ===== --}}
    <section class="u-wrap pt-11">
        <div class="grid grid-cols-[1fr_384px] gap-14">
            <div>
                <div class="u-sec-label"><h2>Últimas publicações</h2><a class="ml-auto text-[12px] font-bold uppercase tracking-[0.08em] text-green-link" href="#">Ver todas</a></div>

                @foreach ($latestLead as $i => $a)
                    @if ($i > 0)<hr class="u-rule">@endif
                    <article class="grid grid-cols-[1fr_280px] gap-7 {{ $i === 0 ? 'pb-7' : 'py-7' }}">
                        <div>
                            <x-site.category :category="$a->category" />
                            <h3 class="u-hed my-2 text-[29px]"><a href="{{ route('artigo', $a->slug) }}">{{ $a->title }}</a></h3>
                            <p class="u-serif mb-[10px] text-[17px] leading-[1.55] text-ink-2">{{ $a->excerpt }}</p>
                            <div class="u-byline">{{ $a->author->name }} · {{ $a->published_at->translatedFormat('j M') }} · {{ $a->reading_minutes }} min</div>
                        </div>
                        <a href="{{ route('artigo', $a->slug) }}" class="self-start"><x-site.thumb ratio="3/2" :media="$a->cover" /></a>
                    </article>
                @endforeach

                <hr class="u-rule">

                <div class="grid grid-cols-3 gap-7 pt-7">
                    @foreach ($latestGrid as $a)
                        <article>
                            <a href="{{ route('artigo', $a->slug) }}"><x-site.thumb ratio="16/9" :media="$a->cover" class="mb-3" /></a>
                            <x-site.category :category="$a->category" />
                            <h3 class="u-hed my-2 text-[19px]"><a href="{{ route('artigo', $a->slug) }}">{{ $a->title }}</a></h3>
                            <div class="u-byline">{{ $a->author->name }} · {{ $a->published_at->translatedFormat('j M') }}</div>
                        </article>
                    @endforeach
                </div>
            </div>

            {{-- Rail: mais lidos --}}
            <aside>
                <div class="u-sec-label"><h2>Mais lidos</h2></div>
                @foreach ($mostRead as $i => $a)
                    @if ($i > 0)<hr class="u-rule">@endif
                    <div class="grid grid-cols-[34px_1fr] gap-[14px] {{ $i === 0 ? 'pb-4' : ($loop->last ? 'pt-4' : 'py-4') }}">
                        <div class="u-serif text-[30px] font-semibold leading-none text-gold">{{ $i + 1 }}</div>
                        <div>
                            <h3 class="u-hed mb-[6px] text-[18px] leading-[1.28]"><a href="{{ route('artigo', $a->slug) }}">{{ $a->title }}</a></h3>
                            <x-site.category :category="$a->category" class="!text-[11px]" />
                        </div>
                    </div>
                @endforeach

                <div class="mt-9 border-2 border-green p-6">
                    <div class="u-serif text-[20px] font-semibold leading-[1.15]">A economia, explicada.</div>
                    <p class="mt-2 text-[13px] leading-[1.55] text-ink-2">A análise da semana no seu email, às sextas.</p>
                    <input type="email" placeholder="o.seu@email.co.ao" class="mt-3 w-full border border-line-strong px-3 py-[10px] text-[13px] text-ink-3 focus:outline-none">
                    <button class="mt-[10px] w-full bg-green py-3 text-[13.5px] font-semibold text-white">Subscrever</button>
                </div>
            </aside>
        </div>
    </section>
    @endif

    {{-- ===== ANÁLISE & OPINIÃO ===== --}}
    @if ($opinionLead)
    <section class="mt-14 border-y border-line bg-panel">
        <div class="u-wrap py-11">
            <div class="u-sec-label"><h2>Análise &amp; Opinião</h2></div>
            <div class="grid grid-cols-[1.5fr_1px_1fr] gap-11">
                <article>
                    <div class="mb-4 flex items-center gap-[18px]">
                        <div class="u-photo h-14 w-14 shrink-0 rounded-full"></div>
                        <div><div class="text-[14px] font-bold">{{ $opinionLead->author->name }}</div><div class="u-byline">{{ $opinionLead->author->title }}</div></div>
                    </div>
                    <h3 class="u-hed mb-[14px] text-[34px] font-medium italic leading-[1.15]"><a href="{{ route('artigo', $opinionLead->slug) }}">{{ $opinionLead->title }}</a></h3>
                    <p class="u-serif max-w-[560px] text-[18px] leading-[1.6] text-ink-2">{{ $opinionLead->excerpt }}</p>
                </article>
                <div class="bg-line-strong"></div>
                <div class="flex flex-col">
                    @foreach ($opinionList as $i => $o)
                        @if ($i > 0)<hr class="border-0 border-t border-line-strong">@endif
                        <article class="{{ $i === 0 ? 'pb-[18px]' : ($loop->last ? 'pt-[18px]' : 'py-[18px]') }}">
                            <x-site.category :category="$o->category" />
                            <h4 class="u-hed my-[6px] text-[19px] font-medium italic"><a href="{{ route('artigo', $o->slug) }}">{{ $o->title }}</a></h4>
                            <div class="u-byline">{{ $o->author->name }}</div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ===== NEWSLETTER ===== --}}
    <section class="bg-green text-[#eaf1ed]">
        <div class="u-wrap flex items-center gap-12 py-11">
            <div class="flex-1">
                <div class="u-serif text-[30px] font-semibold leading-[1.1] text-white">A economia, explicada — todas as sextas.</div>
                <p class="mt-[10px] max-w-[520px] text-[14px] leading-[1.6] text-[#9db8ad]">A análise da semana, sem jargão, no seu email. Grátis e sem spam.</p>
            </div>
            <form class="flex w-[440px] gap-[10px]" action="#" method="post">
                <input type="email" placeholder="o.seu@email.co.ao" class="flex-1 bg-white px-4 py-[14px] text-[14px] text-ink placeholder:text-ink-3 focus:outline-none">
                <button type="submit" class="whitespace-nowrap bg-gold-soft px-[22px] py-[14px] text-[14px] font-bold text-green">Subscrever</button>
            </form>
        </div>
    </section>

    {{-- ===== SECÇÕES ===== --}}
    <section class="u-wrap pt-14">
        <div class="u-sec-label"><h2>Secções</h2></div>
        <div class="grid grid-cols-4 border-t border-line">
            @foreach (\App\Models\Category::active()->orderBy('position')->withCount(['articles' => fn ($q) => $q->published()])->get() as $c)
                <a href="{{ route('categoria', $c->slug) }}"
                   class="border-b border-line px-6 py-5 {{ $loop->iteration % 4 !== 0 ? 'border-r' : '' }} {{ $loop->iteration % 4 === 1 ? 'pl-0' : '' }} {{ $loop->iteration % 4 === 0 ? 'pr-0' : '' }}">
                    <div class="flex items-center gap-2">
                        <span class="inline-block h-[10px] w-[10px]" style="background: {{ $c->color }};"></span>
                        <div class="u-hed text-[20px]">{{ $c->name }}</div>
                    </div>
                    <div class="u-byline mt-1">{{ $c->description }} · {{ $c->articles_count }}</div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
