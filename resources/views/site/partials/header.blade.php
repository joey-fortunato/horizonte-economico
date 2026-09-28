@php
    $sections = config('sections');
    $active = $activeSection ?? null;
    // rótulos curtos para a barra (nomes completos usados nas categorias/kickers)
    $navShort = [
        'economia' => 'Economia',
        'financas-pessoais' => 'Finanças',
        'banca-seguros' => 'Banca',
        'empresas' => 'Empresas',
        'mercados' => 'Mercados',
        'politica-economica' => 'Política',
        'literacia-financeira' => 'Literacia',
    ];
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
        <div class="u-wrap flex h-[32px] items-center gap-5 text-[11.5px]">
            <span class="shrink-0 tracking-[0.02em] text-[#9db8ad]">{{ \Carbon\Carbon::now()->translatedFormat('j \d\e F') }} · Luanda</span>
            <div class="he-ticker-mask ml-auto flex-1">
                <div class="he-ticker tabular-nums text-[#9db8ad]">
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

    {{-- Masthead compacto de linha única: wordmark + nav + acções --}}
    <div class="u-wrap flex h-[60px] items-stretch gap-8">
        <a href="{{ url('/') }}" class="u-serif flex shrink-0 items-center text-[24px] font-bold tracking-[-0.01em] text-white">Horizonte Económico</a>

        <nav class="flex items-stretch gap-6 overflow-hidden">
            @foreach ($sections as $slug => $s)
                <a href="{{ route('categoria', $slug) }}"
                   class="u-nav whitespace-nowrap {{ $active === $slug ? 'u-nav--active text-white' : 'text-[#cfe0d8]' }}">{{ $navShort[$slug] ?? $s['label'] }}</a>
            @endforeach
        </nav>

        <div class="ml-auto flex shrink-0 items-center gap-4">
            <a href="{{ route('pesquisa') }}" aria-label="Pesquisar" class="flex items-center text-[#eaf1ed]">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
            </a>
            <a href="#" class="bg-gold-soft px-4 py-[8px] text-[13px] font-semibold text-green">Subscrever</a>
        </div>
    </div>
</header>
