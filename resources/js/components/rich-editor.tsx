import Link from '@tiptap/extension-link';
import { EditorContent, useEditor } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import {
    Bold,
    Italic,
    Link as LinkIcon,
    List,
    ListOrdered,
    Quote,
    Redo2,
    Undo2,
} from 'lucide-react';
import { type ReactNode } from 'react';

type Props = {
    value: string;
    onChange: (html: string) => void;
};

export default function RichEditor({ value, onChange }: Props) {
    const editor = useEditor({
        immediatelyRender: false,
        extensions: [
            StarterKit.configure({ heading: { levels: [2, 3] } }),
            Link.configure({ openOnClick: false, autolink: true }),
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
        editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
    };

    return (
        <div className="overflow-hidden rounded-md border border-input bg-background">
            <div className="flex flex-wrap items-center gap-1 border-b border-border bg-muted/40 p-1.5">
                <Btn title="Parágrafo" active={editor.isActive('paragraph')} onClick={() => editor.chain().focus().setParagraph().run()}>
                    P
                </Btn>
                <Btn title="Título 2" active={editor.isActive('heading', { level: 2 })} onClick={() => editor.chain().focus().toggleHeading({ level: 2 }).run()}>
                    H2
                </Btn>
                <Btn title="Título 3" active={editor.isActive('heading', { level: 3 })} onClick={() => editor.chain().focus().toggleHeading({ level: 3 }).run()}>
                    H3
                </Btn>
                <span className="mx-1 h-5 w-px bg-border" />
                <Btn title="Negrito" active={editor.isActive('bold')} onClick={() => editor.chain().focus().toggleBold().run()}>
                    <Bold className="size-4" />
                </Btn>
                <Btn title="Itálico" active={editor.isActive('italic')} onClick={() => editor.chain().focus().toggleItalic().run()}>
                    <Italic className="size-4" />
                </Btn>
                <Btn title="Ligação" active={editor.isActive('link')} onClick={setLink}>
                    <LinkIcon className="size-4" />
                </Btn>
                <span className="mx-1 h-5 w-px bg-border" />
                <Btn title="Lista" active={editor.isActive('bulletList')} onClick={() => editor.chain().focus().toggleBulletList().run()}>
                    <List className="size-4" />
                </Btn>
                <Btn title="Lista numerada" active={editor.isActive('orderedList')} onClick={() => editor.chain().focus().toggleOrderedList().run()}>
                    <ListOrdered className="size-4" />
                </Btn>
                <Btn title="Citação" active={editor.isActive('blockquote')} onClick={() => editor.chain().focus().toggleBlockquote().run()}>
                    <Quote className="size-4" />
                </Btn>
                <span className="mx-1 h-5 w-px bg-border" />
                <Btn title="Desfazer" onClick={() => editor.chain().focus().undo().run()}>
                    <Undo2 className="size-4" />
                </Btn>
                <Btn title="Refazer" onClick={() => editor.chain().focus().redo().run()}>
                    <Redo2 className="size-4" />
                </Btn>
            </div>
            <EditorContent editor={editor} />
        </div>
    );
}
