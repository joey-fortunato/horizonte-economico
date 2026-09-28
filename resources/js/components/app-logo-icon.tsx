import type { ImgHTMLAttributes } from 'react';

type Props = ImgHTMLAttributes<HTMLImageElement> & {
    variant?: 'cor' | 'negativo';
};

export default function AppLogoIcon({
    variant = 'cor',
    className,
    alt = 'Horizonte Económico',
    ...props
}: Props) {
    return (
        <img
            src={`/brand/he-simbolo-${variant}.svg`}
            alt={alt}
            className={className}
            {...props}
        />
    );
}
