import ImageExt from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import { EditorContent, useEditor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import {
    Bold,
    Image as ImageIcon,
    Italic,
    Link as LinkIcon,
    List,
    ListOrdered,
    Quote,
    Redo2,
    Undo2,
    Upload,
    X,
} from 'lucide-react';
import { type ReactNode, useEffect, useState } from 'react';
import FigureImage from '@/components/extensions/figure-image';

type Props = {
    value: string;
    onChange: (html: string) => void;
};

type MediaItem = {
    id: number;
    url: string;
    srcset: string | null;
    alt: string | null;
};

// Mantém srcset/sizes no HTML das imagens (responsivas no frontend)
const ResponsiveImage = ImageExt.extend({
    addAttributes() {
        return {
            ...this.parent?.(),
            srcset: { default: null },
            sizes: { default: null },
        };
    },
});

function xsrfToken(): string {
    const m = document.cookie.match(/XSRF-TOKEN=([^;]+)/);
    return m ? decodeURIComponent(m[1]) : '';
}

export default function RichEditor({ value, onChange }: Props) {
    const [pickerOpen, setPickerOpen] = useState(false);

    const editor = useEditor({
        immediatelyRender: false,
        extensions: [
            StarterKit.configure({ heading: { levels: [2, 3] } }),
            Link.configure({ openOnClick: false, autolink: true }),
            ResponsiveImage.configure({
                HTMLAttributes: { class: 'article-image' },
            }),
            FigureImage.configure({
                HTMLAttributes: { class: 'article-figure' },
            }),
        ],
        content: value || '',
        editorProps: {
            attributes: {
                class: 'prose-editor min-h-[320px] px-4 py-3 focus:outline-none',
            },
        },
        onUpdate: ({ editor }) => onChange(editor.getHTML()),
    });

    if (!editor) {
        return null;
    }

    const insertImage = (m: MediaItem) => {
        editor
            .chain()
            .focus()
            .setFigureImage({
                src: m.url,
                alt: m.alt ?? '',
                srcset: m.srcset,
                sizes: '(max-width: 768px) 100vw, 720px',
                caption: m.alt ?? '',
            })
            .run();
        setPickerOpen(false);
    };

    const Btn = ({
        onClick,
        active,
        title,
        children,
    }: {
        onClick: () => void;
        active?: boolean;
        title: string;
        children: ReactNode;
    }) => (
        <button
            type="button"
            title={title}
            onClick={onClick}
            className={`flex h-8 min-w-8 items-center justify-center rounded px-2 text-sm font-semibold ${
                active
                    ? 'bg-primary/10 text-primary'
                    : 'text-muted-foreground hover:bg-muted'
            }`}
        >
            {children}
        </button>
    );

    const setLink = () => {
        const prev = editor.getAttributes('link').href as string | undefined;
        const url = window.prompt('URL da ligação', prev ?? 'https://');
        if (url === null) return;
        if (url === '') {
            editor.chain().focus().extendMarkRange('link').unsetLink().run();
            return;
        }
        editor
            .chain()
            .focus()
            .extendMarkRange('link')
            .setLink({ href: url })
            .run();
    };

    return (
        <div className="overflow-hidden rounded-md border border-input bg-background">
            <div className="flex flex-wrap items-center gap-1 border-b border-border bg-muted/40 p-1.5">
                <Btn
                    title="Parágrafo"
                    active={editor.isActive('paragraph')}
                    onClick={() => editor.chain().focus().setParagraph().run()}
                >
                    P
                </Btn>
                <Btn
                    title="Título 2"
                    active={editor.isActive('heading', { level: 2 })}
                    onClick={() =>
                        editor.chain().focus().toggleHeading({ level: 2 }).run()
                    }
                >
                    H2
                </Btn>
                <Btn
                    title="Título 3"
                    active={editor.isActive('heading', { level: 3 })}
                    onClick={() =>
                        editor.chain().focus().toggleHeading({ level: 3 }).run()
                    }
                >
                    H3
                </Btn>
                <span className="mx-1 h-5 w-px bg-border" />
                <Btn
                    title="Negrito"
                    active={editor.isActive('bold')}
                    onClick={() => editor.chain().focus().toggleBold().run()}
                >
                    <Bold className="size-4" />
                </Btn>
                <Btn
                    title="Itálico"
                    active={editor.isActive('italic')}
                    onClick={() => editor.chain().focus().toggleItalic().run()}
                >
                    <Italic className="size-4" />
                </Btn>
                <Btn
                    title="Ligação"
                    active={editor.isActive('link')}
                    onClick={setLink}
                >
                    <LinkIcon className="size-4" />
                </Btn>
                <Btn title="Imagem" onClick={() => setPickerOpen(true)}>
                    <ImageIcon className="size-4" />
                </Btn>
                <span className="mx-1 h-5 w-px bg-border" />
                <Btn
                    title="Lista"
                    active={editor.isActive('bulletList')}
                    onClick={() =>
                        editor.chain().focus().toggleBulletList().run()
                    }
                >
                    <List className="size-4" />
                </Btn>
                <Btn
                    title="Lista numerada"
                    active={editor.isActive('orderedList')}
                    onClick={() =>
                        editor.chain().focus().toggleOrderedList().run()
                    }
                >
                    <ListOrdered className="size-4" />
                </Btn>
                <Btn
                    title="Citação"
                    active={editor.isActive('blockquote')}
                    onClick={() =>
                        editor.chain().focus().toggleBlockquote().run()
                    }
                >
                    <Quote className="size-4" />
                </Btn>
                <span className="mx-1 h-5 w-px bg-border" />
                <Btn
                    title="Desfazer"
                    onClick={() => editor.chain().focus().undo().run()}
                >
                    <Undo2 className="size-4" />
                </Btn>
                <Btn
                    title="Refazer"
                    onClick={() => editor.chain().focus().redo().run()}
                >
                    <Redo2 className="size-4" />
                </Btn>
            </div>
            <EditorContent editor={editor} />

            {pickerOpen && (
                <MediaPicker
                    onClose={() => setPickerOpen(false)}
                    onSelect={insertImage}
                />
            )}
        </div>
    );
}

function MediaPicker({
    onClose,
    onSelect,
}: {
    onClose: () => void;
    onSelect: (m: MediaItem) => void;
}) {
    const [items, setItems] = useState<MediaItem[]>([]);
    const [loading, setLoading] = useState(true);
    const [uploading, setUploading] = useState(false);

    const load = () => {
        setLoading(true);
        fetch('/media/list', {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        })
            .then((r) => r.json())
            .then((data: MediaItem[]) => setItems(data))
            .finally(() => setLoading(false));
    };

    useEffect(load, []);

    const upload = (file: File) => {
        setUploading(true);
        const body = new FormData();
        body.append('file', file);
        fetch('/media/upload', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-XSRF-TOKEN': xsrfToken(),
            },
            body,
        })
            .then((r) => r.json())
            .then((m: MediaItem) => onSelect(m))
            .finally(() => setUploading(false));
    };

    return (
        <div
            className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
            onClick={onClose}
        >
            <div
                className="flex max-h-[80vh] w-full max-w-3xl flex-col overflow-hidden rounded-xl border border-border bg-card"
                onClick={(e) => e.stopPropagation()}
            >
                <div className="flex items-center justify-between border-b border-border px-5 py-3">
                    <div className="font-semibold">Inserir imagem</div>
                    <div className="flex items-center gap-3">
                        <label className="inline-flex cursor-pointer items-center gap-2 rounded-md bg-primary px-3 py-1.5 text-sm font-semibold text-primary-foreground">
                            <Upload className="size-4" />
                            {uploading ? 'A carregar…' : 'Carregar'}
                            <input
                                type="file"
                                accept="image/*"
                                className="hidden"
                                onChange={(e) => {
                                    const f = e.target.files?.[0];
                                    if (f) upload(f);
                                }}
                            />
                        </label>
                        <button
                            type="button"
                            onClick={onClose}
                            aria-label="Fechar"
                            className="text-muted-foreground"
                        >
                            <X className="size-5" />
                        </button>
                    </div>
                </div>
                <div className="grid grid-cols-3 gap-3 overflow-y-auto p-5 sm:grid-cols-4">
                    {loading && (
                        <p className="col-span-full text-sm text-muted-foreground">
                            A carregar…
                        </p>
                    )}
                    {!loading && items.length === 0 && (
                        <p className="col-span-full text-sm text-muted-foreground">
                            Ainda não há imagens. Carregue uma acima.
                        </p>
                    )}
                    {items.map((m) => (
                        <button
                            type="button"
                            key={m.id}
                            onClick={() => onSelect(m)}
                            className="overflow-hidden rounded-md border border-border hover:border-primary"
                        >
                            <img
                                src={m.url}
                                alt={m.alt ?? ''}
                                className="aspect-video w-full object-cover"
                            />
                        </button>
                    ))}
                </div>
            </div>
        </div>
    );
}
