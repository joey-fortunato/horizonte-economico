@props([
    'category' => null, // App\Models\Category (opcional)
    'slug' => null,
    'label' => null,
    'color' => null,
    'variant' => 'default', // default | lead | large
    'href' => null,
])

@php
    // Precedência: model > props explícitas > config (fallback por slug)
    if ($category) {
        $slug = $category->slug;
        $label = $category->name;
        $color = $category->color;
    }
    $cfg = $slug ? config('sections.'.$slug) : null;
    $label = $label ?? ($cfg['label'] ?? \Illuminate\Support\Str::headline((string) $slug));
    $color = $color ?? ($cfg['color'] ?? '#0b5c47');
    $url = $href ?? ($slug ? url('categoria/'.$slug) : null);
    $dot = $variant === 'lead' || $variant === 'large' ? 11 : 9;
    $text = $variant === 'lead' ? 'text-[13px]' : ($variant === 'large' ? 'text-[12.5px]' : 'text-[12px]');
    $tag = $url ? 'a' : 'span';
@endphp

<{{ $tag }}
    @if ($url) href="{{ $url }}" @endif
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 font-sans font-bold uppercase tracking-[0.1em] '.$text]) }}
    style="color: {{ $color }};"
>
    <span aria-hidden="true" style="width: {{ $dot }}px; height: {{ $dot }}px; background: {{ $color }};"></span>
    {{ $label }}
</{{ $tag }}>

@if ($variant === 'lead')
    <span aria-hidden="true" class="mt-2 block h-[2px] w-9" style="background: {{ $color }};"></span>
@endif
