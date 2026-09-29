import { Head, router, useForm } from '@inertiajs/react';
import { Trash2, Upload } from 'lucide-react';
import { type FormEvent } from 'react';
import { dashboard } from '@/routes';

type MediaItem = {
    id: number;
    url: string | null;
    alt_text: string | null;
    mime_type: string | null;
    size: number | null;
    in_use: boolean;
};

type Props = {
    media: {
        data: MediaItem[];
        links: { url: string | null; label: string; active: boolean }[];
    };
};

export default function MediaIndex({ media }: Props) {
    const form = useForm({ file: null as File | null, alt_text: '' });

    const submit = (e: FormEvent) => {
        e.preventDefault();
        form.post('/media', { forceFormData: true, onSuccess: () => form.reset() });
    };

    return (
        <>
            <Head title="Media" />
            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <h1 className="font-serif text-2xl font-bold tracking-tight">
                    Biblioteca multimédia
                </h1>

                <form
                    onSubmit={submit}
                    className="flex flex-wrap items-end gap-4 rounded-xl border border-border bg-card p-5"
                >
                    <div>
                        <label className="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                            Imagem (máx. 5 MB)
                        </label>
                        <input
                            type="file"
                            accept="image/*"
                            onChange={(e) =>
                                form.setData('file', e.target.files?.[0] ?? null)
                            }
                            className="text-sm"
                        />
                    </div>
                    <div className="flex-1">
                        <label className="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted-foreground">
                            Texto alternativo
                        </label>
                        <input
                            className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:border-ring focus:outline-none"
                            value={form.data.alt_text}
                            onChange={(e) => form.setData('alt_text', e.target.value)}
                        />
                    </div>
                    <button
                        type="submit"
                        disabled={form.processing || !form.data.file}
                        className="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground disabled:opacity-60"
                    >
                        <Upload className="size-4" />
                        Carregar
                    </button>
                </form>

                <div className="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-6">
                    {media.data.map((m) => (
                        <div
                            key={m.id}
                            className="overflow-hidden rounded-lg border border-border bg-card"
                        >
                            <div className="aspect-video bg-muted">
                                {m.url && (
                                    <img
                                        src={m.url}
                                        alt={m.alt_text ?? ''}
                                        className="h-full w-full object-cover"
                                    />
                                )}
                            </div>
                            <div className="flex items-center justify-between p-2">
                                <span
                                    className={`text-[10px] font-semibold uppercase ${m.in_use ? 'text-primary' : 'text-muted-foreground'}`}
                                >
                                    {m.in_use ? 'Em uso' : 'Livre'}
                                </span>
                                {!m.in_use && (
                                    <button
                                        onClick={() =>
                                            router.delete(`/media/${m.id}`)
                                        }
                                        className="text-destructive"
                                        aria-label="Eliminar"
                                    >
                                        <Trash2 className="size-4" />
                                    </button>
                                )}
                            </div>
                        </div>
                    ))}
                    {media.data.length === 0 && (
                        <p className="col-span-full py-10 text-center text-muted-foreground">
                            Ainda não há imagens carregadas.
                        </p>
                    )}
                </div>
            </div>
        </>
    );
}

MediaIndex.layout = {
    breadcrumbs: [
        { title: 'Painel', href: dashboard() },
        { title: 'Media', href: '/media' },
    ],
};
