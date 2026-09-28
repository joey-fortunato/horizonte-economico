@extends('site.layouts.app')

@section('title', 'Horizonte Económico — Economia, análise e literacia financeira')
@section('meta_description', 'Notícias, análise e explicações económicas focadas em Angola. Compreender a economia para tomar melhores decisões.')

@php
    $lead = [
        'section' => 'politica-economica',
        'title' => 'OGE 2027: onde vai o dinheiro do Estado e o que muda para as famílias',
        'standfirst' => 'Uma leitura das prioridades orçamentais, do peso da dívida e das medidas com impacto directo no rendimento disponível e no custo de vida.',
        'author' => 'João Muanza',
        'role' => 'Editor de Economia',
        'read' => '8 min de leitura',
        'updated' => 'Actualizado há 12 min',
        'caption' => 'Ministério das Finanças, Luanda · Arquivo HE',
    ];
    $secondary = [
        ['section' => 'mercados', 'title' => 'Kwanza recupera face ao dólar após leilão cambial do BNA', 'byline' => 'Ana Cardoso · há 1 h'],
        ['section' => 'banca-seguros', 'title' => 'Novas regras de crédito: o que muda ao pedir financiamento', 'byline' => 'Pedro Lemos · há 3 h'],
        ['section' => 'financas-pessoais', 'title' => 'Como montar um fundo de emergência com salário em kwanzas', 'byline' => 'Marta Songo · há 5 h'],
    ];
    $latestLead = [
        ['section' => 'empresas', 'title' => 'Startups angolanas captam ronda recorde em 2026', 'standfirst' => 'O ecossistema tecnológico atrai investidores regionais com foco em fintech e logística, num ano de recuperação do investimento privado.', 'byline' => 'Bruno Kiala · 24 Set · 5 min'],
        ['section' => 'economia', 'title' => 'PIB não-petrolífero cresce acima do esperado no 3.º trimestre', 'standfirst' => 'Agricultura e serviços puxam a actividade económica e reduzem a dependência do crude.', 'byline' => 'Ana Cardoso · 23 Set · 6 min'],
    ];
    $latestGrid = [
        ['section' => 'banca-seguros', 'title' => 'Seguro automóvel: guia para escolher a cobertura certa', 'byline' => 'Pedro Lemos · 22 Set'],
        ['section' => 'literacia-financeira', 'title' => 'Juros compostos explicados com exemplos do dia a dia', 'byline' => 'Marta Songo · 21 Set'],
        ['section' => 'mercados', 'title' => 'Procura por Obrigações do Tesouro sobe no leilão', 'byline' => 'João Muanza · 20 Set'],
    ];
    $mostRead = [
        ['Salário mínimo em 2027: o que se sabe até agora', 'economia'],
        ['Como declarar IRT sem erros — passo a passo', 'literacia-financeira'],
        ['Depósitos a prazo: onde rende mais o kwanza', 'banca-seguros'],
        ['Câmbio informal vs. oficial: porquê a diferença', 'mercados'],
        ['Abrir empresa em Angola: custos e prazos reais', 'empresas'],
    ];
    $opinionList = [
        ['Coluna', 'O kwanza forte é uma bênção envenenada?', 'Ana Cardoso'],
        ['Análise', 'Porque a inflação teima em não descer aos dois dígitos', 'Pedro Lemos'],
        ['Convidado', 'O que falta para a banca financiar as PME', 'Economista convidado'],
    ];
@endphp

@section('content')
    {{-- ===== LEAD ===== --}}
    <section class="u-wrap pt-9">
        <div class="grid grid-cols-[1fr_1px_384px] gap-[38px]">
            {{-- Manchete --}}
            <article>
                <div class="mb-4"><x-site.category :slug="$lead['section']" variant="lead" /></div>
                <h1 class="u-hed mb-[18px] text-[54px] leading-[1.04]">
                    <a href="{{ url('artigo/oge-2027') }}">{{ $lead['title'] }}</a>
                </h1>
                <p class="u-serif mb-5 max-w-[680px] text-[22px] leading-[1.5] text-ink-2">{{ $lead['standfirst'] }}</p>
                <div class="u-byline mb-5 flex items-center gap-[10px]">
                    Por <b class="font-semibold text-ink">{{ $lead['author'] }}</b>, {{ $lead['role'] }} · {{ $lead['read'] }}
                    <span class="text-line-strong">·</span>
                    <span class="flex items-center gap-[5px] font-semibold text-gold">
                        <span class="inline-block h-[6px] w-[6px] rounded-full bg-gold"></span>{{ $lead['updated'] }}
                    </span>
                </div>
                <x-site.thumb ratio="3/2" :caption="$lead['caption']" />
            </article>

            {{-- Fio vertical --}}
            <div class="bg-line"></div>

            {{-- Secundárias (com thumbnail 16:9) --}}
            <div class="flex flex-col">
                @foreach ($secondary as $i => $s)
                    @if ($i > 0)<hr class="u-rule">@endif
                    <article class="{{ $i === 0 ? 'pb-5' : 'py-5' }}">
                        <x-site.thumb ratio="16/9" class="mb-3" />
                        <x-site.category :slug="$s['section']" />
                        <h2 class="u-hed my-2 text-[22px]"><a href="#">{{ $s['title'] }}</a></h2>
                        <div class="u-byline">{{ $s['byline'] }}</div>
                    </article>
                @endforeach
            </div>
        </div>
        <hr class="u-rule mt-11">
    </section>

    {{-- ===== ÚLTIMAS + MAIS LIDOS ===== --}}
    <section class="u-wrap pt-11">
        <div class="grid grid-cols-[1fr_384px] gap-14">
            {{-- River --}}
            <div>
                <div class="u-sec-label"><h2>Últimas publicações</h2><a class="ml-auto text-[12px] font-bold uppercase tracking-[0.08em] text-green-link" href="#">Ver todas</a></div>

                @foreach ($latestLead as $i => $a)
                    @if ($i > 0)<hr class="u-rule">@endif
                    <article class="grid grid-cols-[1fr_280px] gap-7 {{ $i === 0 ? 'pb-7' : 'py-7' }}">
                        <div>
                            <x-site.category :slug="$a['section']" />
                            <h3 class="u-hed my-2 text-[29px]"><a href="#">{{ $a['title'] }}</a></h3>
                            <p class="u-serif mb-[10px] text-[17px] leading-[1.55] text-ink-2">{{ $a['standfirst'] }}</p>
                            <div class="u-byline">{{ $a['byline'] }}</div>
                        </div>
                        <x-site.thumb ratio="3/2" class="self-start" />
                    </article>
                @endforeach

                <hr class="u-rule">

                {{-- Grelha de três com thumbnail 16:9 --}}
                <div class="grid grid-cols-3 gap-7 pt-7">
                    @foreach ($latestGrid as $a)
                        <article>
                            <x-site.thumb ratio="16/9" class="mb-3" />
                            <x-site.category :slug="$a['section']" />
                            <h3 class="u-hed my-2 text-[19px]"><a href="#">{{ $a['title'] }}</a></h3>
                            <div class="u-byline">{{ $a['byline'] }}</div>
                        </article>
                    @endforeach
                </div>
            </div>

            {{-- Rail: mais lidos --}}
            <aside>
                <div class="u-sec-label"><h2>Mais lidos</h2></div>
                @foreach ($mostRead as $i => [$title, $slug])
                    @if ($i > 0)<hr class="u-rule">@endif
                    <div class="grid grid-cols-[34px_1fr] gap-[14px] {{ $i === 0 ? 'pb-4' : ($loop->last ? 'pt-4' : 'py-4') }}">
                        <div class="u-serif text-[30px] font-semibold leading-none text-gold">{{ $i + 1 }}</div>
                        <div>
                            <h3 class="u-hed mb-[6px] text-[18px] leading-[1.28]"><a href="#">{{ $title }}</a></h3>
                            <x-site.category :slug="$slug" class="!text-[11px]" />
                        </div>
                    </div>
                @endforeach

                {{-- caixa newsletter compacta no rail para encher o fim da coluna --}}
                <div class="mt-9 border-2 border-green p-6">
                    <div class="u-serif text-[20px] font-semibold leading-[1.15]">A economia, explicada.</div>
                    <p class="mt-2 text-[13px] leading-[1.55] text-ink-2">A análise da semana no seu email, às sextas.</p>
                    <input type="email" placeholder="o.seu@email.co.ao" class="mt-3 w-full border border-line-strong px-3 py-[10px] text-[13px] text-ink-3 focus:outline-none">
                    <button class="mt-[10px] w-full bg-green py-3 text-[13.5px] font-semibold text-white">Subscrever</button>
                </div>
            </aside>
        </div>
    </section>

    {{-- ===== ANÁLISE & OPINIÃO (banda) ===== --}}
    <section class="mt-14 border-y border-line bg-panel">
        <div class="u-wrap py-11">
            <div class="u-sec-label"><h2>Análise &amp; Opinião</h2></div>
            <div class="grid grid-cols-[1.5fr_1px_1fr] gap-11">
                <article>
                    <div class="mb-4 flex items-center gap-[18px]">
                        <div class="u-photo h-14 w-14 shrink-0 rounded-full"></div>
                        <div><div class="text-[14px] font-bold">João Muanza</div><div class="u-byline">Editor de Economia</div></div>
                    </div>
                    <h3 class="u-hed mb-[14px] text-[34px] font-medium italic leading-[1.15]"><a href="#">A dívida não é o problema de Angola. A execução orçamental é.</a></h3>
                    <p class="u-serif max-w-[560px] text-[18px] leading-[1.6] text-ink-2">Todos os anos o debate fixa-se no rácio da dívida. Mas o que trava o país é a incapacidade de transformar orçamento aprovado em investimento realizado — e isso raramente aparece nos números do costume.</p>
                </article>
                <div class="bg-line-strong"></div>
                <div class="flex flex-col">
                    @foreach ($opinionList as $i => [$tag, $title, $author])
                        @if ($i > 0)<hr class="border-0 border-t border-line-strong">@endif
                        <article class="{{ $i === 0 ? 'pb-[18px]' : ($loop->last ? 'pt-[18px]' : 'py-[18px]') }}">
                            <span class="u-kicker text-gold">{{ $tag }}</span>
                            <h4 class="u-hed my-[6px] text-[19px] font-medium italic"><a href="#">{{ $title }}</a></h4>
                            <div class="u-byline">{{ $author }}</div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ===== NEWSLETTER (banda verde) ===== --}}
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

    {{-- ===== SECÇÕES (directório com código de cor) ===== --}}
    <section class="u-wrap pt-14">
        <div class="u-sec-label"><h2>Secções</h2></div>
        <div class="grid grid-cols-4 border-t border-line">
            @foreach (config('sections') as $slug => $s)
                <a href="{{ url('categoria/'.$slug) }}"
                   class="border-b border-line px-6 py-5 {{ $loop->iteration % 4 !== 0 ? 'border-r' : '' }} {{ $loop->iteration % 4 === 1 ? 'pl-0' : '' }} {{ $loop->iteration % 4 === 0 ? 'pr-0' : '' }}">
                    <div class="flex items-center gap-2">
                        <span class="inline-block h-[10px] w-[10px]" style="background: {{ $s['color'] }};"></span>
                        <div class="u-hed text-[20px]">{{ $s['label'] }}</div>
                    </div>
                    <div class="u-byline mt-1">{{ $s['desc'] }}</div>
                </a>
            @endforeach
            <a href="#" class="border-b border-line py-5 pl-6">
                <div class="u-hed text-[20px] text-green-link">Ver todas →</div>
                <div class="u-byline mt-1">7 categorias</div>
            </a>
        </div>
    </section>
@endsection
