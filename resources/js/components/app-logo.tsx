import { usePage } from '@inertiajs/react';

import AppLogoIcon from '@/components/app-logo-icon';

export default function AppLogo() {
    const { name } = usePage().props;

    return (
        <>
            <div className="flex aspect-square size-8 items-center justify-center">
                <AppLogoIcon variant="negativo" className="size-8" />
            </div>
            <div className="ml-1 grid flex-1 text-left">
                <span className="font-serif truncate text-base leading-tight font-bold">
                    {name}
                </span>
            </div>
        </>
    );
}
