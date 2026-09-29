@php $ga = config('services.analytics.ga_id'); @endphp
@if ($ga)
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        // Privacidade primeiro: analítica negada por defeito (Consent Mode)
        gtag('consent', 'default', { analytics_storage: 'denied' });
        gtag('config', '{{ $ga }}', { anonymize_ip: true });
        try {
            if (localStorage.getItem('he_consent') === 'granted') {
                gtag('consent', 'update', { analytics_storage: 'granted' });
            }
        } catch (e) {}
    </script>

    <div id="he-consent" style="display:none;position:fixed;left:0;right:0;bottom:0;z-index:60;background:#123b30;color:#eaf1ed;">
        <div class="u-wrap" style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;padding-top:14px;padding-bottom:14px;">
            <p style="flex:1;min-width:260px;margin:0;font-size:13px;line-height:1.6;color:#cfe0d8;">
                Usamos cookies de analítica para compreender a leitura e melhorar o Horizonte Económico.
                Consulte a <a href="{{ url('privacidade') }}" style="color:#d4ad67;text-decoration:underline;">Política de Privacidade</a>.
            </p>
            <div style="display:flex;gap:10px;">
                <button type="button" data-consent="denied" style="border:1px solid #2c5446;background:transparent;color:#eaf1ed;padding:9px 16px;font-size:13px;font-weight:600;cursor:pointer;">Recusar</button>
                <button type="button" data-consent="granted" style="border:none;background:#d4ad67;color:#123b30;padding:9px 16px;font-size:13px;font-weight:700;cursor:pointer;">Aceitar</button>
            </div>
        </div>
    </div>
    <script>
        (function () {
            var el = document.getElementById('he-consent');
            var stored;
            try { stored = localStorage.getItem('he_consent'); } catch (e) {}
            if (!stored && el) { el.style.display = 'block'; }
            if (el) {
                el.querySelectorAll('[data-consent]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var v = btn.getAttribute('data-consent');
                        try { localStorage.setItem('he_consent', v); } catch (e) {}
                        if (v === 'granted' && typeof gtag === 'function') {
                            gtag('consent', 'update', { analytics_storage: 'granted' });
                        }
                        el.style.display = 'none';
                    });
                });
            }
        })();
    </script>
@endif
