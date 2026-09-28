@extends('site.layouts.app')

@section('title', 'Sobre nós — Horizonte Económico')
@section('meta_description', 'O Horizonte Económico é uma plataforma de informação, análise e literacia económica focada em Angola.')

@php
    $valores = [
        ['Clareza', 'Distinguimos factos, análise e opinião. Escrevemos para ser compreendidos por qualquer leitor.'],
        ['Credibilidade', 'Citamos fontes, datamos os artigos e assinalamos correcções sem apagar o histórico editorial.'],
        ['Utilidade', 'Ajudamos as pessoas a tomar decisões financeiras mais informadas no dia a dia.'],
    ];
    $stats = [['840+', 'Artigos publicados'], ['7', 'Categorias editoriais'], ['12', 'Autores e colaboradores'], ['180 mil', 'Leitores por mês']];
    $equipa = [['João Muanza', 'Editor de Economia'], ['Ana Cardoso', 'Mercados'], ['Marta Songo', 'Finanças pessoais'], ['Pedro Lemos', 'Banca & seguros']];
@endphp

@section('content')
    {{-- statement hero (banda verde) --}}
    <section class="bg-green text-white">
        <div class="u-wrap py-16 text-center">
            <div class="text-[12px] font-bold uppercase tracking-[0.14em]" style="color:var(--color-gold-soft)">Sobre nós</div>
            <h1 class="u-serif mx-auto my-4 max-w-[820px] text-[50px] font-bold leading-[1.1] tracking-[-0.02em] text-white">Compreender a economia para tomar melhores decisões</h1>
            <p class="u-serif mx-auto max-w-[640px] text-[19px] leading-[1.6] text-[#cfe0d8]">Uma plataforma de informação, análise e literacia económica, focada em Angola. Explicamos os factos e o seu contexto — sem jargão desnecessário.</p>
        </div>
    </section>

    {{-- valores (colunas com filetes) --}}
    <section class="u-wrap pt-13" style="padding-top:52px">
        <div class="grid grid-cols-3 border-y border-line">
            @foreach ($valores as $i => [$titulo, $texto])
                <div class="py-7 {{ $i === 0 ? 'pr-7' : ($i === 2 ? 'pl-7' : 'px-7') }} {{ $i !== 2 ? 'border-r border-line' : '' }}">
                    <div class="u-serif text-[22px] font-semibold">{{ $titulo }}</div>
                    <p class="u-serif mt-[10px] text-[16px] leading-[1.6] text-ink-2">{{ $texto }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- estatísticas (banda) --}}
    <section class="mt-13 border-y border-line bg-panel" style="margin-top:52px">
        <div class="u-wrap grid grid-cols-4 gap-6 py-10 text-center">
            @foreach ($stats as [$num, $label])
                <div>
                    <div class="u-serif text-[44px] font-bold text-green">{{ $num }}</div>
                    <div class="u-byline mt-1">{{ $label }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- equipa --}}
    <section class="u-wrap pt-13" style="padding-top:52px">
        <div class="mb-6 flex items-baseline border-b-2 border-green pb-[10px]"><h2 class="u-hed text-[23px]">A equipa editorial</h2></div>
        <div class="grid grid-cols-4 gap-8 pb-16">
            @foreach ($equipa as [$nome, $cargo])
                <div>
                    <x-site.thumb ratio="4/5" class="mb-[14px]" />
                    <div class="u-hed text-[19px]">{{ $nome }}</div>
                    <div class="u-byline mt-1">{{ $cargo }}</div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
