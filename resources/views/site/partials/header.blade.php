@php
    $sections = [
        'economia' => 'Economia',
        'financas-pessoais' => 'Finanças pessoais',
        'banca-seguros' => 'Banca & seguros',
        'empresas' => 'Empresas',
        'mercados' => 'Mercados',
        'politica-economica' => 'Política económica',
        'literacia-financeira' => 'Literacia financeira',
    ];
    $active = $activeSection ?? null;
    $markets = [
        ['USD/AOA', '912,4', '+0,3%', true],
        ['Inflação', '19,7%', '−0,4', false],
        ['Brent', '78,1', '+1,1%', true],
        ['BODIVA', '1 284', '+0,6%', true],
    ];
@endphp

<header class="bg-green text-[#eaf1ed] {{ ($stickyHeader ?? true) ? 'sticky top-0 z-50' : '' }}">
    {{-- Faixa de mercados — data fixa + ticker deslizante confinado à secção --}}
    <div class="border-b border-green-2">
        <div class="u-wrap flex h-[38px] items-center gap-6 text-[12px]">
            <span class="shrink-0 tracking-[0.02em] text-[#9db8ad]">{{ \Carbon\Carbon::now()->translatedFormat('l, j \d\e F \d\e Y') }} · Luanda</span>
            <span class="shrink-0 border-l border-green-2 pl-6 text-[11px] font-bold uppercase tracking-[0.1em] text-[#8fb0a4]">Mercados</span>
            {{-- máscara: overflow-hidden garante que o slide não ultrapassa a secção --}}
            <div class="he-ticker-mask ml-auto flex-1">
                <div class="he-ticker tabular-nums text-[#9db8ad]">
                    {{-- duas cópias idênticas → translateX(-50%) faz loop sem cortes --}}
                    @for ($copy = 0; $copy < 2; $copy++)
                        <div class="flex items-center gap-8 pr-8" @if ($copy === 1) aria-hidden="true" @endif>
                            @foreach ($markets as [$label, $value, $delta, $up])
                                <span>{{ $label }} <b class="font-semibold text-white">{{ $value }}</b>
                                    <span class="{{ $up ? 'text-[#7fd0a8]' : 'text-[#e6a5a5]' }}">{{ $delta }}</span></span>
                            @endforeach
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    {{-- Masthead --}}
    <div class="u-wrap flex items-center py-5">
        <div class="w-[190px] text-[12px] text-[#9db8ad]">Edição de Luanda</div>
        <div class="flex-1 text-center">
            <a href="{{ url('/') }}" class="u-serif block text-[40px] font-bold tracking-[-0.02em] leading-none text-white">Horizonte Económico</a>
            <div class="mt-2 text-[11px] uppercase tracking-[0.34em] text-[#8fb0a4]">Economia · Análise · Literacia financeira</div>
        </div>
        <div class="flex w-[190px] items-center justify-end gap-4">
            <button type="button" aria-label="Pesquisar" class="text-[#eaf1ed]">
                <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            </button>
            <a href="#" class="bg-gold-soft px-4 py-[9px] text-[13px] font-semibold text-green">Subscrever</a>
        </div>
    </div>

    {{-- Navegação --}}
    <nav class="border-t border-green-2">
        <div class="u-wrap flex h-[46px] items-stretch gap-7">
            @foreach ($sections as $slug => $label)
                <a href="{{ url('categoria/'.$slug) }}"
                   class="u-nav {{ $active === $slug ? 'u-nav--active text-white' : 'text-[#cfe0d8]' }}">{{ $label }}</a>
            @endforeach
            <a href="#" class="u-nav ml-auto text-[#8fb0a4]">Opinião</a>
        </div>
    </nav>
</header>
