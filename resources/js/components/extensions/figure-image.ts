import { mergeAttributes, Node } from '@tiptap/core';

export interface FigureImageOptions {
    HTMLAttributes: Record<string, unknown>;
}

declare module '@tiptap/core' {
    interface Commands<ReturnType> {
        figureImage: {
            setFigureImage: (attrs: {
                src: string;
                alt?: string | null;
                srcset?: string | null;
                sizes?: string | null;
                caption?: string | null;
            }) => ReturnType;
        };
    }
}

/**
 * Imagem com legenda editável: <figure><img><figcaption>…</figcaption></figure>.
 * O conteúdo do nó é a legenda (inline*), editável no próprio editor.
 */
export const FigureImage = Node.create<FigureImageOptions>({
    name: 'figureImage',
    group: 'block',
    content: 'inline*',
    draggable: true,
    isolating: true,

    addOptions() {
        return { HTMLAttributes: {} };
    },

    addAttributes() {
        return {
            src: { default: null },
            alt: { default: null },
            srcset: { default: null },
            sizes: { default: null },
        };
    },

    parseHTML() {
        return [
            {
                tag: 'figure',
                contentElement: 'figcaption',
                getAttrs: (el) => {
                    const img = (el as HTMLElement).querySelector('img');
                    if (!img) return false;
                    return {
                        src: img.getAttribute('src'),
                        alt: img.getAttribute('alt'),
                        srcset: img.getAttribute('srcset'),
                        sizes: img.getAttribute('sizes'),
                    };
                },
            },
        ];
    },

    renderHTML({ HTMLAttributes }) {
        const { src, alt, srcset, sizes, ...rest } = HTMLAttributes;
        return [
            'figure',
            mergeAttributes(this.options.HTMLAttributes, rest),
            ['img', { src, alt, srcset, sizes }],
            ['figcaption', {}, 0],
        ];
    },

    addCommands() {
        return {
            setFigureImage:
                (attrs) =>
                ({ chain }) =>
                    chain()
                        .insertContent({
                            type: this.name,
                            attrs: {
                                src: attrs.src,
                                alt: attrs.alt ?? null,
                                srcset: attrs.srcset ?? null,
                                sizes: attrs.sizes ?? null,
                            },
                            content: attrs.caption
                                ? [{ type: 'text', text: attrs.caption }]
                                : [],
                        })
                        .run(),
        };
    },
});

export default FigureImage;
