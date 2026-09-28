import { Link } from '@inertiajs/react';
import AppLogoIcon from '@/components/app-logo-icon';
import { home } from '@/routes';
import type { AuthLayoutProps } from '@/types';

export default function AuthSplitLayout({
    children,
    title,
    description,
}: AuthLayoutProps) {
    const features = [
        'Editor de conteúdo moderno',
        'Agendamento de publicações',
        'Permissões por perfil editorial',
    ];

    return (
        <div className="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div className="relative hidden h-full flex-col justify-between bg-[#123b30] p-12 text-white lg:flex">
                <Link href={home()} className="relative z-20 flex items-center">
                    <img
                        src="/brand/he-horizontal-negativo.svg"
                        alt="Horizonte Económico"
                        className="h-8 w-auto"
                    />
                </Link>

                <div className="relative z-20">
                    <h2 className="font-serif text-4xl leading-tight font-bold">
                        Backoffice editorial
                    </h2>
                    <p className="mt-4 max-w-md text-[15px] leading-relaxed text-[#cfe0d8]">
                        Gere artigos, categorias, autores e media num só lugar.
                        Publica com rigor e regularidade.
                    </p>
                    <ul className="mt-8 space-y-4">
                        {features.map((f) => (
                            <li
                                key={f}
                                className="flex items-center gap-3 text-[14px] text-white/90"
                            >
                                <svg
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#d4ad67"
                                    strokeWidth="2.4"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                >
                                    <path d="M20 6 9 17l-5-5" />
                                </svg>
                                {f}
                            </li>
                        ))}
                    </ul>
                </div>

                <p className="relative z-20 text-xs text-white/60">
                    © {new Date().getFullYear()} Horizonte Económico · Acesso
                    restrito
                </p>
            </div>

            <div className="w-full lg:p-8">
                <div className="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <Link
                        href={home()}
                        className="relative z-20 flex items-center justify-center lg:hidden"
                    >
                        <AppLogoIcon variant="cor" className="h-12 w-12" />
                    </Link>
                    <div className="flex flex-col items-start gap-2 text-left sm:items-center sm:text-center">
                        <h1 className="font-serif text-2xl font-semibold">
                            {title}
                        </h1>
                        <p className="text-sm text-balance text-muted-foreground">
                            {description}
                        </p>
                    </div>
                    {children}
                </div>
            </div>
        </div>
    );
}
