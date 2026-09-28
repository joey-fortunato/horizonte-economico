@props([
    'ratio' => '16/9', // aspect-ratio: adequa a dimensão ao contexto da secção
    'src' => null,
    'alt' => '',
    'caption' => null,
])

<figure {{ $attributes->merge(['class' => 'm-0']) }}>
    <div class="u-photo relative w-full overflow-hidden" style="aspect-ratio: {{ $ratio }};">
        @if ($src)
            <img src="{{ $src }}" alt="{{ $alt }}" loading="lazy"
                 class="absolute inset-0 h-full w-full object-cover">
        @else
            {{-- placeholder com marca subtil enquanto não há fotografia real --}}
            <span class="absolute inset-0 flex items-center justify-center text-line-strong">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.3">
                    <rect x="3" y="4" width="18" height="16" rx="1"/><circle cx="8.5" cy="9.5" r="1.6"/><path d="m3 17 5-4 4 3 4-4 5 4"/>
                </svg>
            </span>
        @endif
        @if ($caption)
            <figcaption class="absolute bottom-2 left-2 text-[11px] text-ink-3">{{ $caption }}</figcaption>
        @endif
    </div>
</figure>
