<footer class="mt-16 bg-green text-[#b7c9c0]">
    <div class="u-wrap grid grid-cols-[1.7fr_1fr_1fr_1fr] gap-11 pt-13 pb-8" style="padding-top:52px;">
        <div>
            <img src="{{ asset('brand/he-horizontal-negativo.svg') }}" alt="Horizonte Económico" class="h-8 w-auto mb-1">
            <p class="mt-3 max-w-[320px] text-[13.5px] leading-[1.75] text-[#8fb0a4]">
                Informação e análise económica para decisões mais informadas. Compreender a economia para viver melhor.
            </p>
        </div>
        @foreach ([
            'Secções' => ['Economia', 'Finanças pessoais', 'Mercados', 'Empresas'],
            'Institucional' => ['Sobre nós', 'Política editorial', 'Privacidade', 'Termos'],
            'Seguir' => ['Newsletter', 'LinkedIn', 'Facebook', 'X (Twitter)'],
        ] as $heading => $links)
            <div>
                <div class="mb-3 text-[12px] font-semibold uppercase tracking-[0.1em] text-white">{{ $heading }}</div>
                <div class="flex flex-col gap-[10px] text-[13.5px]">
                    @foreach ($links as $link)
                        <a href="#" class="hover:text-white">{{ $link }}</a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <div class="border-t border-green-2">
        <div class="u-wrap flex justify-between py-[18px] text-[12px] text-[#6f887d]">
            <span>© {{ date('Y') }} Horizonte Económico. Todos os direitos reservados.</span>
            <span>Luanda · Angola</span>
        </div>
    </div>
</footer>
