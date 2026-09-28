@extends('site.layouts.app')

@php
    $article = [
        'section' => 'politica-economica',
        'title' => 'OGE 2027: onde vai o dinheiro do Estado e o que muda para as famílias',
        'standfirst' => 'Uma leitura das prioridades orçamentais, do peso da dívida e das medidas com impacto directo no rendimento das famílias angolanas.',
        'author' => 'João Muanza',
        'role' => 'Editor de Economia',
        'date' => '28 de Setembro de 2026',
        'read' => '8 min de leitura',
        'cover_caption' => 'Sede do Ministério das Finanças, Luanda. Fotografia: Arquivo Horizonte Económico.',
    ];
    $sec = config('sections.'.$article['section']);
    $toc = ['As grandes prioridades', 'O peso da dívida', 'O que muda para as famílias'];
    $tags = ['OGE 2027', 'Fiscalidade', 'Dívida pública', 'IRT'];
    $sources = [
        ['Proposta de Orçamento Geral do Estado 2027', 'Ministério das Finanças', 'documento oficial'],
        ['Relatório de Inflação, Setembro de 2026', 'Banco Nacional de Angola', 'ver fonte'],
        ['Contas Nacionais, 3.º trimestre de 2026', 'INE', 'ver fonte'],
    ];
    $related = [
        ['section' => 'politica-economica', 'title' => 'Como o IRT é calculado em 2026', 'meta' => '6 min de leitura'],
        ['section' => 'economia', 'title' => 'Dívida pública: perguntas frequentes', 'meta' => '5 min de leitura'],
        ['section' => 'financas-pessoais', 'title' => 'Rendimento disponível: o que é', 'meta' => '4 min de leitura'],
    ];
@endphp

@section('title', $article['title'].' — Horizonte Económico')
@section('meta_description', $article['standfirst'])
@section('og_type', 'article')

@section('content')
    {{-- ===== Barra de contexto + ferramentas de leitura ===== --}}
    <div class="text-[#cfe0d8]" style="background:#0e3228;">
        <div class="u-wrap flex h-11 items-center text-[13px]">
            <a href="{{ url('categoria/'.$article['section']) }}" class="flex items-center gap-2 font-semibold text-white">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="{{ $sec['color'] }}" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Voltar a {{ $sec['label'] }}
            </a>
            <div class="ml-auto flex items-center gap-2">
                <span class="mr-1 text-[#8fb0a4]">Ferramentas de leitura</span>
                <span class="flex items-center gap-[7px] border border-green-2 px-[11px] py-[6px] text-[#eaf1ed]"><b class="text-[11px]">A</b><b class="text-[15px]">A</b></span>
                <span class="flex items-center gap-[7px] border border-green-2 px-[11px] py-[6px] text-[#eaf1ed]"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M6 4h12v16l-6-4-6 4z"/></svg>Guardar</span>
                <span class="flex items-center gap-[7px] border border-green-2 px-[11px] py-[6px] text-[#eaf1ed]"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M11 5 6 9H2v6h4l5 4z"/><path d="M15.5 8.5a5 5 0 0 1 0 7"/></svg>Ouvir</span>
                <span class="flex items-center gap-[7px] border border-green-2 px-[11px] py-[6px] text-[#eaf1ed]"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 13.5 6.8 4M15.4 6.5l-6.8 4"/></svg>Partilhar</span>
            </div>
        </div>
    </div>
    {{-- progresso de leitura --}}
    <div class="h-[3px] bg-line"><div class="h-full w-[38%] bg-gold"></div></div>

    {{-- ===== Cabeçalho do artigo ===== --}}
    <div class="u-wrap pt-9">
        <div class="mx-auto max-w-[760px]">
            <div class="u-byline flex gap-2">Início <span class="text-line-strong">/</span> <a href="{{ url('categoria/'.$article['section']) }}" class="font-semibold" style="color:{{ $sec['color'] }}">{{ $sec['label'] }}</a></div>
            <div class="my-4"><x-site.category :slug="$article['section']" variant="lead" /></div>
            <h1 class="u-hed mb-[18px] text-[48px] leading-[1.08]">{{ $article['title'] }}</h1>
            <p class="u-serif mb-6 text-[23px] leading-[1.5] text-ink-2">{{ $article['standfirst'] }}</p>
            <div class="flex items-center gap-[14px] border-y border-line py-4">
                <div class="u-byline text-[14px]">Por <b class="font-semibold text-ink">{{ $article['author'] }}</b>, {{ $article['role'] }}</div>
                <span class="text-line-strong">·</span><span class="u-byline">{{ $article['date'] }}</span>
                <span class="u-byline ml-auto">{{ $article['read'] }}</span>
            </div>
        </div>

        {{-- capa --}}
        <div class="mx-auto mt-8 max-w-[1000px]">
            <x-site.thumb ratio="16/9" :caption="$article['cover_caption']" />
        </div>

        {{-- ===== Corpo: rail de partilha + prosa + aside ===== --}}
        <div class="mt-11 grid grid-cols-[64px_minmax(0,720px)_300px] justify-center gap-12">
            {{-- rail partilha --}}
            <div class="sticky top-5 flex flex-col gap-[10px] self-start">
                @foreach (['whatsapp','facebook','linkedin','x'] as $net)
                    <span class="flex h-[42px] w-[42px] items-center justify-center border border-line-strong text-ink">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><rect x="3" y="3" width="18" height="18" rx="2" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>
                    </span>
                @endforeach
            </div>

            {{-- prosa --}}
            <div class="u-prose" style="font-family:var(--font-serif)">
                <p class="mb-6 text-[20px] leading-[1.72] text-[#232d27] [&::first-letter]:float-left [&::first-letter]:mr-3 [&::first-letter]:mt-2 [&::first-letter]:text-[74px] [&::first-letter]:font-semibold [&::first-letter]:leading-[0.72] [&::first-letter]:text-ink">O Orçamento Geral do Estado para 2027 chega num momento de transição. Depois de dois anos de ajustamento, o Executivo aposta em recuperar investimento público sem perder de vista o controlo da dívida — um equilíbrio difícil que definirá o custo de vida no próximo ano.</p>
                <p class="mb-6 text-[20px] leading-[1.72] text-[#232d27]">Neste artigo explicamos, sem jargão, para onde vai cada kwanza previsto no orçamento, quais os sectores que ganham peso e o que isso significa, na prática, para o rendimento disponível das famílias.</p>

                <h2 class="u-hed mb-[14px] mt-10 text-[28px] leading-[1.2]">As grandes prioridades</h2>
                <p class="mb-6 text-[20px] leading-[1.72] text-[#232d27]">Educação e saúde continuam a absorver a maior fatia da despesa social,<a href="#fontes" class="align-super text-[12px] font-bold text-green-link" style="font-family:var(--font-sans)">1</a> mas é no investimento em infra-estruturas que se nota a maior variação face ao ano anterior.</p>

                <blockquote class="my-8 border-l-[3px] border-gold pl-6">
                    <p class="text-[27px] font-medium leading-[1.32] text-ink">A verdadeira prova do orçamento não está no que promete, mas no que consegue executar.</p>
                </blockquote>

                <p class="mb-6 text-[20px] leading-[1.72] text-[#232d27]">O documento prevê ainda uma revisão da tabela do IRT, com potencial alívio para os escalões mais baixos.</p>

                <h2 class="u-hed mb-[14px] mt-10 text-[28px] leading-[1.2]">O peso da dívida</h2>
                <p class="mb-6 text-[20px] leading-[1.72] text-[#232d27]">O serviço da dívida mantém-se como uma das rubricas mais pesadas.<a href="#fontes" class="align-super text-[12px] font-bold text-green-link" style="font-family:var(--font-sans)">2</a> Cada ponto percentual de juro representa recursos que deixam de estar disponíveis para despesa produtiva.</p>

                {{-- tabela de dados --}}
                <div class="my-[30px] border border-line bg-panel p-6" style="font-family:var(--font-sans)">
                    <div class="border-b-2 border-green pb-[10px] text-[12px] font-bold uppercase tracking-[0.08em] text-ink">Despesa por função · proposta 2027</div>
                    @foreach ([['Sectores sociais', 76, '38%', 'bg-green'], ['Investimento público', 48, '24%', 'bg-green'], ['Serviço da dívida', 42, '21%', 'bg-gold'], ['Outros', 34, '17%', 'bg-line-strong']] as $row)
                        <div class="flex items-center justify-between py-3 {{ ! $loop->last ? 'border-b border-line-strong' : '' }}">
                            <span class="text-[15px]">{{ $row[0] }}</span>
                            <div class="flex items-center gap-[14px]">
                                <span class="inline-block h-[6px] w-[180px] bg-white"><span class="{{ $row[3] }} block h-full" style="width: {{ $row[1] }}%"></span></span>
                                <b class="text-[15px] tabular-nums">{{ $row[2] }}</b>
                            </div>
                        </div>
                    @endforeach
                    <div class="u-byline mt-[10px]">Fonte: Proposta de OGE 2027, Ministério das Finanças.</div>
                </div>

                <p class="mb-6 text-[20px] leading-[1.72] text-[#232d27]">Em suma, o OGE 2027 procura reconciliar disciplina orçamental com crescimento. O sucesso dependerá, como sempre, da execução.</p>

                {{-- nota de correção --}}
                <div class="mt-8 border-l-[3px] border-gold p-[14px_18px]" style="background:#faf3e2;font-family:var(--font-sans)">
                    <div class="mb-1 text-[11px] font-bold uppercase tracking-[0.08em] text-gold">Nota de correcção</div>
                    <p class="text-[13.5px] leading-[1.6] text-ink-2">Corrigido a 26 Set 2026: a versão inicial indicava 18% em vez de 19,7% para a taxa de inflação homóloga. O histórico editorial é preservado.</p>
                </div>

                {{-- fontes --}}
                <div id="fontes" class="mt-7 border border-line bg-panel p-6" style="font-family:var(--font-sans)">
                    <div class="border-b-2 border-green pb-3 text-[12px] font-bold uppercase tracking-[0.08em] text-ink">Fontes e referências</div>
                    <ol class="mt-[14px] flex list-decimal flex-col gap-[11px] pl-[22px]">
                        @foreach ($sources as $s)
                            <li class="text-[14px] leading-[1.55] text-ink-2">{{ $s[0] }} — <b class="font-semibold text-ink">{{ $s[1] }}</b> · <a href="#" class="font-semibold text-green-link">{{ $s[2] }} ↗</a></li>
                        @endforeach
                    </ol>
                </div>

                {{-- etiquetas --}}
                <div class="mt-8 flex flex-wrap gap-[10px] border-t border-line pt-[22px]" style="font-family:var(--font-sans)">
                    @foreach ($tags as $t)
                        <a href="#" class="border border-line-strong px-[13px] py-[6px] text-[12px] font-semibold text-ink-2">{{ $t }}</a>
                    @endforeach
                </div>
            </div>

            {{-- aside --}}
            <aside class="sticky top-5 self-start">
                <div class="border-b-2 border-green pb-[10px] text-[11px] font-bold uppercase tracking-[0.1em] text-ink-3">Neste artigo</div>
                <div class="flex flex-col">
                    @foreach ($toc as $i => $item)
                        <a href="#" class="border-b border-line py-[11px] text-[14px] {{ $i === 0 ? 'font-semibold text-green-link' : 'text-ink-2' }}">{{ $item }}</a>
                    @endforeach
                </div>
                <div class="mt-7 bg-green p-[22px_20px] text-[#eaf1ed]">
                    <div class="u-serif text-[20px] font-semibold leading-[1.2] text-white">Não perca a análise da semana</div>
                    <input type="email" placeholder="o.seu@email.co.ao" class="my-[14px] w-full bg-white px-[13px] py-[10px] text-[13px] text-ink-3 focus:outline-none">
                    <button class="w-full bg-gold-soft py-[11px] text-[13.5px] font-bold text-green">Subscrever</button>
                </div>
            </aside>
        </div>
    </div>

    {{-- caixa de autor (banda) --}}
    <div class="mt-14 border-y border-line bg-panel">
        <div class="u-wrap py-9">
            <div class="mx-auto flex max-w-[1000px] items-start gap-[22px]">
                <div class="u-photo h-[76px] w-[76px] shrink-0 rounded-full bg-white"></div>
                <div class="max-w-[640px]">
                    <div class="u-byline font-bold uppercase tracking-[0.1em] text-gold">Sobre o autor</div>
                    <div class="u-hed my-[6px] text-[22px]">{{ $article['author'] }}</div>
                    <p class="text-[14.5px] leading-[1.65] text-ink-2" style="font-family:var(--font-sans)">Economista e jornalista. Escreve sobre política económica e contas públicas há mais de dez anos. <a href="{{ url('autor/joao-muanza') }}" class="font-semibold text-green-link">Ver todos os artigos →</a></p>
                </div>
            </div>
        </div>
    </div>

    {{-- relacionadas com thumbnails proporcionais --}}
    <div class="u-wrap pt-13" style="padding-top:52px">
        <div class="mx-auto max-w-[1000px]">
            <div class="mb-6 flex items-baseline border-b-2 border-green pb-[10px]"><h2 class="u-hed text-[23px]">Continue a ler</h2></div>
            <div class="grid grid-cols-3 gap-7">
                @foreach ($related as $r)
                    <article>
                        <x-site.thumb ratio="3/2" class="mb-[14px]" />
                        <x-site.category :slug="$r['section']" />
                        <h3 class="u-hed my-2 text-[20px]"><a href="#">{{ $r['title'] }}</a></h3>
                        <div class="u-byline">{{ $r['meta'] }}</div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
@endsection
