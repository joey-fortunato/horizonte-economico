@extends('site.layouts.app')

@php
    $docs = [
        'politica-editorial' => 'Política editorial',
        'privacidade' => 'Privacidade',
        'termos' => 'Termos de utilização',
    ];
    $title = $docs[$page];
    $toc = match ($page) {
        'politica-editorial' => ['Princípios editoriais', 'Factos, análise e opinião', 'Fontes e verificação', 'Correcções e actualizações', 'Conflitos de interesse', 'Contacto editorial'],
        'privacidade' => ['Dados recolhidos', 'Finalidade do tratamento', 'Cookies e analítica', 'Os seus direitos', 'Conservação', 'Contacto'],
        'termos' => ['Aceitação', 'Uso permitido', 'Propriedade intelectual', 'Responsabilidade', 'Alterações', 'Contacto'],
    };
@endphp

@section('title', $title.' — Horizonte Económico')
@section('meta_description', $title.' do Horizonte Económico.')

@section('content')
    <section class="u-wrap pt-10">
        <div class="u-byline">Início <span class="text-line-strong">/</span> Institucional</div>
        <h1 class="u-serif mt-3 mb-[6px] text-[44px] font-bold">{{ $title }}</h1>
        <p class="u-byline">Última actualização: 15 de Setembro de 2026 · 6 min de leitura</p>

        <div class="grid grid-cols-[250px_1fr] gap-13 pb-16 pt-8" style="gap:52px">
            {{-- índice + trocador de documentos --}}
            <aside class="sticky top-5 self-start">
                <div class="u-byline mb-3 font-bold uppercase tracking-[0.08em]">Nesta página</div>
                <nav class="flex flex-col">
                    @foreach ($toc as $i => $item)
                        <a href="#" class="border-l-2 py-[10px] pl-[14px] text-[14px] {{ $i === 0 ? 'border-green font-semibold text-green-link' : 'border-line text-ink-2' }}">{{ $item }}</a>
                    @endforeach
                </nav>
                <div class="mt-6 border border-line bg-panel p-4">
                    <div class="u-byline mb-[10px] font-bold uppercase tracking-[0.06em]">Documentos</div>
                    <div class="flex flex-col gap-[9px] text-[13.5px]">
                        @foreach ($docs as $slug => $label)
                            <a href="{{ url($slug) }}" class="{{ $slug === $page ? 'font-semibold text-green-link' : 'text-ink-2' }}">{{ $label }}</a>
                        @endforeach
                    </div>
                </div>
            </aside>

            {{-- conteúdo --}}
            <div class="max-w-[680px]" style="font-family:var(--font-serif)">
                @if ($page === 'politica-editorial')
                    <p class="mb-[18px] text-[20px] leading-[1.72] text-ink-2">O Horizonte Económico rege-se por princípios de rigor, transparência e independência. Este documento descreve como produzimos, verificamos e corrigimos os nossos conteúdos.</p>
                    <h2 class="u-hed mt-9 mb-3 text-[26px]">Princípios editoriais</h2>
                    <p class="mb-[18px] text-[18px] leading-[1.72] text-[#232d27]">Comprometemo-nos a informar com exactidão e a contextualizar os acontecimentos económicos. O objectivo é ajudar o leitor a compreender causas e consequências, não apenas relatar factos isolados.</p>
                    <h2 class="u-hed mt-9 mb-3 text-[26px]">Factos, análise e opinião</h2>
                    <p class="mb-[18px] text-[18px] leading-[1.72] text-[#232d27]">Distinguimos claramente três tipos de conteúdo, sinalizados na etiqueta do artigo:</p>
                    <ul class="mb-[18px] list-disc pl-[22px] text-[18px] leading-[1.7] text-[#232d27]">
                        <li class="mb-2"><b>Factos:</b> informação verificável, com fontes identificadas.</li>
                        <li class="mb-2"><b>Análise:</b> interpretação dos factos por especialistas da redacção.</li>
                        <li class="mb-2"><b>Opinião:</b> posição pessoal do autor, claramente assinada.</li>
                    </ul>
                    <h2 class="u-hed mt-9 mb-3 text-[26px]">Correcções e actualizações</h2>
                    <p class="mb-[18px] text-[18px] leading-[1.72] text-[#232d27]">Quando corrigimos um erro, mantemos o histórico editorial e apresentamos uma nota de correcção no fim do artigo, com data e natureza da alteração.</p>
                    <div class="my-[18px] border-l-[3px] border-gold p-[14px_18px]" style="background:#faf3e2;font-family:var(--font-sans)">
                        <div class="mb-1 text-[11px] font-bold uppercase tracking-[0.08em] text-gold">Nota de correcção — exemplo</div>
                        <p class="text-[13.5px] leading-[1.6] text-ink-2">Corrigido a 26 Set 2026: a versão inicial indicava 18% em vez de 19,7% para a inflação homóloga.</p>
                    </div>
                    <h2 class="u-hed mt-9 mb-3 text-[26px]">Contacto editorial</h2>
                    <p class="mb-[18px] text-[18px] leading-[1.72] text-[#232d27]">Para reportar um erro ou sugerir um tema, escreva para <b class="text-green-link">redacao@horizonteeconomico.com</b>.</p>
                @else
                    <p class="mb-[18px] text-[20px] leading-[1.72] text-ink-2">Este documento descreve as condições relativas a <b>{{ \Illuminate\Support\Str::lower($title) }}</b> no Horizonte Económico. O conteúdo integral será disponibilizado na implementação final.</p>
                    @foreach ($toc as $item)
                        <h2 class="u-hed mt-9 mb-3 text-[26px]">{{ $item }}</h2>
                        <p class="mb-[18px] text-[18px] leading-[1.72] text-[#232d27]">Secção reservada para o texto de “{{ $item }}”. Recolhemos apenas os dados mínimos necessários e mantemos práticas alinhadas com a legislação aplicável.</p>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endsection
