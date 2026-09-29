import { Head, Link, router, useForm } from '@inertiajs/react';
import { ArrowLeft, Check, ExternalLink, FileText, X } from 'lucide-react';
import { dashboard } from '@/routes';

type Item = {
    id: number;
    title: string;
    url: string;
    source: string | null;
    source_name: string | null;
    description: string | null;
    published_at: string | null;
    fetched_at: string;
    status: string;
    statusLabel: string;
    score: number;
    highlight: boolean;
    terms: string[];
    notes: { id: number; note: string; author: string; at: string }[];
    articles: { title: string; slug: string; status: string }[];
};

type Props = {
    item: Item;
    canDecide: boolean;
    canConvert: boolean;
};

export default function NewsShow({ item, canDecide, canConvert }: Props) {
    const noteForm = useForm({ note: '' });

    const decide = (decision: string) =>
        router.post(`/noticias/${item.id}/decisao`, { decision }, { preserveScroll: true });

    return (
        <>
            <Head title={item.title} />
            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <Link
                    href="/noticias"
                    className="flex w-fit items-center gap-2 text-sm font-semibold text-muted-foreground"
                >
                    <ArrowLeft className="size-4" />
                    Central de Notícias
                </Link>

                <div className="grid gap-6 lg:grid-cols-[1fr_320px]">
                    {/* Detalhe */}
                    <div className="flex flex-col gap-5">
                        <div className="rounded-xl border border-border bg-card p-6">
                            <div className="mb-2 flex items-center gap-2 text-xs uppercase tracking-wide text-muted-foreground">
                                <span>{item.source_name ?? item.source ?? 'Fonte desconhecida'}</span>
                                <span>·</span>
                                <span>{item.published_at ?? 'sem data'}</span>
                            </div>
                            <h1 className="font-serif text-2xl font-bold leading-tight">
                                {item.title}
                            </h1>
                            {item.description && (
                                <p className="mt-4 leading-relaxed text-muted-foreground">
                                    {item.description}
                                </p>
                            )}
                            <a
                                href={item.url}
                                target="_blank"
                                rel="noopener"
                                className="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-primary"
                            >
                                <ExternalLink className="size-4" />
                                Abrir fonte original
                            </a>
                            <p className="mt-4 text-xs text-muted-foreground">
                                O conteúdo integral não é copiado. Redija um artigo próprio a partir da referência.
                            </p>
                        </div>

                        {/* Notas editoriais */}
                        <div className="rounded-xl border border-border bg-card p-6">
                            <div className="mb-4 font-semibold">Notas editoriais</div>
                            <div className="flex flex-col gap-3">
                                {item.notes.length === 0 && (
                                    <p className="text-sm text-muted-foreground">Ainda sem notas.</p>
                                )}
                                {item.notes.map((n) => (
                                    <div key={n.id} className="border-l-2 border-border pl-3">
                                        <p className="text-sm">{n.note}</p>
                                        <p className="mt-1 text-xs text-muted-foreground">
                                            {n.author} · {n.at}
                                        </p>
                                    </div>
                                ))}
                            </div>
                            {canDecide && (
                                <form
                                    onSubmit={(e) => {
                                        e.preventDefault();
                                        noteForm.post(`/noticias/${item.id}/nota`, {
                                            preserveScroll: true,
                                            onSuccess: () => noteForm.reset(),
                                        });
                                    }}
                                    className="mt-4 flex gap-2"
                                >
                                    <input
                                        value={noteForm.data.note}
                                        onChange={(e) => noteForm.setData('note', e.target.value)}
                                        placeholder="Adicionar nota…"
                                        className="flex-1 rounded-md border border-input bg-background px-3 py-2 text-sm"
                                    />
                                    <button
                                        type="submit"
                                        disabled={noteForm.processing || !noteForm.data.note}
                                        className="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground disabled:opacity-60"
                                    >
                                        Adicionar
                                    </button>
                                </form>
                            )}
                        </div>
                    </div>

                    {/* Acções */}
                    <div className="flex flex-col gap-5">
                        <div className="rounded-xl border border-border bg-card p-5">
                            <div className="mb-1 text-xs uppercase tracking-wide text-muted-foreground">
                                Estado editorial
                            </div>
                            <div className="mb-4 font-serif text-lg font-semibold">
                                {item.statusLabel}
                            </div>

                            {canDecide && (
                                <div className="flex gap-2">
                                    <button
                                        onClick={() => decide('select')}
                                        className="inline-flex flex-1 items-center justify-center gap-2 rounded-md bg-primary px-3 py-2 text-sm font-semibold text-primary-foreground"
                                    >
                                        <Check className="size-4" />
                                        Seleccionar
                                    </button>
                                    <button
                                        onClick={() => decide('reject')}
                                        className="inline-flex flex-1 items-center justify-center gap-2 rounded-md border border-destructive/40 px-3 py-2 text-sm font-semibold text-destructive"
                                    >
                                        <X className="size-4" />
                                        Rejeitar
                                    </button>
                                </div>
                            )}

                            {canConvert && item.status !== 'rejected' && (
                                <button
                                    onClick={() =>
                                        router.post(`/noticias/${item.id}/converter`, {})
                                    }
                                    className="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-md border border-border px-3 py-2 text-sm font-semibold"
                                >
                                    <FileText className="size-4" />
                                    Criar rascunho de artigo
                                </button>
                            )}
                        </div>

                        <div className="rounded-xl border border-border bg-card p-5">
                            <div className="mb-1 flex items-center justify-between">
                                <span className="text-xs uppercase tracking-wide text-muted-foreground">
                                    Relevância editorial
                                </span>
                                {item.highlight && (
                                    <span className="rounded-full bg-[#f4ead6] px-2 py-0.5 text-xs font-semibold text-[#7a5a12]">
                                        Melhor
                                    </span>
                                )}
                            </div>
                            <div className="font-serif text-2xl font-bold">{item.score}</div>
                            {item.terms.length > 0 ? (
                                <div className="mt-3">
                                    <div className="mb-1 text-xs text-muted-foreground">
                                        Alinhamento com a linha editorial:
                                    </div>
                                    <div className="flex flex-wrap gap-1.5">
                                        {item.terms.map((t) => (
                                            <span
                                                key={t}
                                                className="rounded border border-border px-2 py-0.5 text-xs text-muted-foreground"
                                            >
                                                {t}
                                            </span>
                                        ))}
                                    </div>
                                </div>
                            ) : (
                                <p className="mt-2 text-xs text-muted-foreground">
                                    Sem termos da linha editorial detectados.
                                </p>
                            )}
                        </div>

                        {item.articles.length > 0 && (
                            <div className="rounded-xl border border-border bg-card p-5">
                                <div className="mb-3 font-semibold">Artigos associados</div>
                                <div className="flex flex-col gap-2">
                                    {item.articles.map((a) => (
                                        <Link
                                            key={a.slug}
                                            href={`/artigos/${a.slug}/editar`}
                                            className="text-sm text-primary hover:underline"
                                        >
                                            {a.title}{' '}
                                            <span className="text-muted-foreground">({a.status})</span>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}

NewsShow.layout = {
    breadcrumbs: [
        { title: 'Painel', href: dashboard() },
        { title: 'Central de Notícias', href: '/noticias' },
    ],
};
