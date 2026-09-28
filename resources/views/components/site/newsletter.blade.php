@props([
    'variant' => 'band', // band (verde) | rail (borda) | aside (verde compacto)
    'source' => 'site',
])

@php $done = session('newsletter_status'); @endphp

@if ($variant === 'band')
    <div class="flex flex-wrap items-center gap-8 md:flex-nowrap md:gap-12">
        <div class="flex-1">
            <div class="u-serif text-[30px] font-semibold leading-[1.1] text-white">A economia, explicada — todas as sextas.</div>
            <p class="mt-[10px] max-w-[520px] text-[14px] leading-[1.6] text-[#9db8ad]">A análise da semana, sem jargão, no seu email. Grátis e sem spam.</p>
        </div>
        <form action="{{ route('newsletter.store') }}" method="post" class="w-full max-w-[460px]">
            @csrf
            <input type="hidden" name="source" value="{{ $source }}">
            <div class="flex gap-[10px]">
                <input type="email" name="email" required placeholder="o.seu@email.co.ao" class="flex-1 bg-white px-4 py-[14px] text-[14px] text-ink placeholder:text-ink-3 focus:outline-none">
                <button type="submit" class="whitespace-nowrap bg-gold-soft px-[22px] py-[14px] text-[14px] font-bold text-green">Subscrever</button>
            </div>
            <label class="mt-3 flex items-start gap-2 text-[12px] leading-snug text-[#9db8ad]">
                <input type="checkbox" name="consent" value="1" required class="mt-[2px] accent-[#d4ad67]">
                Autorizo receber comunicações e aceito a <a href="{{ url('privacidade') }}" class="underline">Política de Privacidade</a>.
            </label>
            @error('email')<p class="mt-1 text-[12px] text-[#e6a5a5]">{{ $message }}</p>@enderror
            @error('consent')<p class="mt-1 text-[12px] text-[#e6a5a5]">{{ $message }}</p>@enderror
            @if ($done)<p class="mt-2 text-[12px] font-semibold text-[#7fd0a8]">{{ $done }}</p>@endif
        </form>
    </div>
@elseif ($variant === 'rail')
    <div class="mt-9 border-2 border-green p-6">
        <div class="u-serif text-[20px] font-semibold leading-[1.15]">A economia, explicada.</div>
        <p class="mt-2 text-[13px] leading-[1.55] text-ink-2">A análise da semana no seu email, às sextas.</p>
        <form action="{{ route('newsletter.store') }}" method="post">
            @csrf
            <input type="hidden" name="source" value="{{ $source }}">
            <input type="email" name="email" required placeholder="o.seu@email.co.ao" class="mt-3 w-full border border-line-strong px-3 py-[10px] text-[13px] text-ink focus:outline-none focus:border-green">
            <label class="mt-2 flex items-start gap-2 text-[11.5px] leading-snug text-ink-3">
                <input type="checkbox" name="consent" value="1" required class="mt-[2px] accent-[#123b30]">
                Aceito a <a href="{{ url('privacidade') }}" class="underline">Política de Privacidade</a>.
            </label>
            <button type="submit" class="mt-[10px] w-full bg-green py-3 text-[13.5px] font-semibold text-white">Subscrever</button>
            @error('email')<p class="mt-1 text-[12px] text-[#a03535]">{{ $message }}</p>@enderror
            @error('consent')<p class="mt-1 text-[12px] text-[#a03535]">{{ $message }}</p>@enderror
            @if ($done)<p class="mt-2 text-[12px] font-semibold text-green-link">{{ $done }}</p>@endif
        </form>
    </div>
@else
    {{-- aside (verde compacto) --}}
    <div class="mt-3 bg-green p-[22px_20px] text-[#eaf1ed]">
        <div class="u-serif text-[20px] font-semibold leading-[1.2] text-white">Não perca a análise da semana</div>
        <form action="{{ route('newsletter.store') }}" method="post">
            @csrf
            <input type="hidden" name="source" value="{{ $source }}">
            <input type="email" name="email" required placeholder="o.seu@email.co.ao" class="my-[14px] w-full bg-white px-[13px] py-[10px] text-[13px] text-ink focus:outline-none">
            <label class="mb-3 flex items-start gap-2 text-[11.5px] leading-snug text-[#9db8ad]">
                <input type="checkbox" name="consent" value="1" required class="mt-[2px] accent-[#d4ad67]">
                Aceito a <a href="{{ url('privacidade') }}" class="underline">Política de Privacidade</a>.
            </label>
            <button type="submit" class="w-full bg-gold-soft py-[11px] text-[13.5px] font-bold text-green">Subscrever</button>
            @error('email')<p class="mt-1 text-[12px] text-[#e6a5a5]">{{ $message }}</p>@enderror
            @error('consent')<p class="mt-1 text-[12px] text-[#e6a5a5]">{{ $message }}</p>@enderror
            @if ($done)<p class="mt-2 text-[12px] font-semibold text-[#7fd0a8]">{{ $done }}</p>@endif
        </form>
    </div>
@endif
