@extends('site.layouts.app')

@section('title', 'Contactos — Horizonte Económico')
@section('meta_description', 'Sugestões de temas, correcções, parcerias ou imprensa. Fale com a redacção do Horizonte Económico.')

@section('content')
    <section class="mx-auto w-full max-w-[1080px] px-10 pt-13" style="padding-top:52px">
        <div class="border-b-2 border-green pb-5">
            <h1 class="u-serif text-[44px] font-bold">Fale connosco</h1>
            <p class="u-serif mt-[10px] max-w-[600px] text-[18px] leading-[1.55] text-ink-2">Sugestões de temas, correcções, parcerias ou imprensa. Respondemos habitualmente em 48 horas úteis.</p>
        </div>

        <div class="grid grid-cols-[1.4fr_1fr] gap-12 pb-16 pt-9">
            {{-- formulário --}}
            <form action="#" method="post" class="flex flex-col">
                <div class="grid grid-cols-2 gap-[18px]">
                    <label class="block"><span class="mb-2 block text-[12px] font-bold uppercase tracking-[0.04em] text-ink-3">Nome</span><input type="text" placeholder="Nome completo" class="w-full border border-line-strong px-[14px] py-3 text-[14px] text-ink placeholder:text-ink-3 focus:outline-none focus:border-green"></label>
                    <label class="block"><span class="mb-2 block text-[12px] font-bold uppercase tracking-[0.04em] text-ink-3">Email</span><input type="email" placeholder="o.seu@email.co.ao" class="w-full border border-line-strong px-[14px] py-3 text-[14px] text-ink placeholder:text-ink-3 focus:outline-none focus:border-green"></label>
                </div>
                <label class="mt-[18px] block"><span class="mb-2 block text-[12px] font-bold uppercase tracking-[0.04em] text-ink-3">Assunto</span>
                    <select class="w-full appearance-none border border-line-strong bg-white px-[14px] py-3 text-[14px] text-ink-3 focus:outline-none focus:border-green">
                        <option>Selecionar assunto</option><option>Sugestão de tema</option><option>Correcção</option><option>Parceria</option><option>Imprensa</option>
                    </select>
                </label>
                <label class="mt-[18px] block"><span class="mb-2 block text-[12px] font-bold uppercase tracking-[0.04em] text-ink-3">Mensagem</span><textarea rows="6" placeholder="Escreva a sua mensagem…" class="w-full border border-line-strong px-[14px] py-3 text-[14px] text-ink placeholder:text-ink-3 focus:outline-none focus:border-green"></textarea></label>
                <label class="mt-[18px] flex items-start gap-[10px] text-[13px] leading-[1.5] text-ink-2"><input type="checkbox" class="mt-[2px] h-[18px] w-[18px] shrink-0 accent-green">Autorizo o tratamento dos meus dados para efeitos de resposta a este contacto, nos termos da Política de Privacidade.</label>
                <button type="submit" class="mt-[22px] w-fit bg-green px-7 py-[14px] text-[14px] font-semibold text-white">Enviar mensagem</button>
            </form>

            {{-- info (banda) --}}
            <div class="border border-line bg-panel p-[30px]">
                @foreach ([
                    ['Email editorial', 'redacao@horizonteeconomico.com'],
                    ['Telefone', '+244 923 000 000'],
                    ['Redacção', 'Talatona, Luanda · Angola'],
                ] as $i => [$label, $value])
                    <div class="{{ $i === 0 ? 'pb-5' : 'py-5' }} border-b border-line-strong">
                        <div class="mb-2 text-[12px] font-bold uppercase tracking-[0.04em] text-ink-3">{{ $label }}</div>
                        <div class="text-[15px] font-semibold">{{ $value }}</div>
                    </div>
                @endforeach
                <div class="pt-5">
                    <div class="mb-2 text-[12px] font-bold uppercase tracking-[0.04em] text-gold">Assessoria de imprensa</div>
                    <p class="text-[13.5px] leading-[1.6] text-ink-2">Jornalistas e parceiros de media: <b class="text-green-link">imprensa@horizonteeconomico.com</b></p>
                </div>
            </div>
        </div>
    </section>
@endsection
