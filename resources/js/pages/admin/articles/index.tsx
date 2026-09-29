import { Head, Link, router } from '@inertiajs/react';
import { Pencil, Plus, Trash2 } from 'lucide-react';
import { dashboard } from '@/routes';

type Row = {
    id: number;
    title: string;
    slug: string;
    category: string | null;
    color: string | null;
    author: string;
    status: string;
    statusLabel: string;
    updated: string;
    canEdit: boolean;
    canPublish: boolean;
    canDelete: boolean;
};

type Props = {
    articles: {
        data: Row[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    filters: { status: string | null; q: string | null };
    counts: Record<string, number>;
    statuses: { value: string; label: string }[];
};

function statusClasses(status: string): string {
    switch (status) {
        case 'published':
            return 'bg-primary/10 text-primary';
        case 'scheduled':
            return 'bg-[#f4ead6] text-[#7a5a12]';
        case 'review':
            return 'bg-destructive/10 text-destructive';
        case 'archived':
            return 'bg-muted text-muted-foreground';
        default:
            return 'bg-muted text-muted-foreground';
    }
}

export default function ArticlesIndex({
    articles,
    filters,
    counts,
    statuses,
}: Props) {
    const total = Object.values(counts).reduce((a, b) => a + b, 0);

    const filterBy = (status: string | null) => {
        router.get('/artigos', status ? { status } : {}, {
            preserveState: true,
            replace: true,
        });
    };

    return (
        <>
            <Head title="Artigos" />

            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <div className="flex flex-wrap items-center gap-4">
                    <div>
                        <h1 className="font-serif text-2xl font-bold tracking-tight">
                            Artigos
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            {total} artigos no total
                        </p>
                    </div>
                    <Link
                        href="/artigos/novo"
                        className="ml-auto inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground"
                    >
                        <Plus className="size-4" />
                        Novo artigo
                    </Link>
                </div>

                {/* Filtros por estado */}
                <div className="flex flex-wrap gap-2 border-b border-border pb-3">
                    <button
                        onClick={() => filterBy(null)}
                        className={`text-sm font-semibold ${!filters.status ? 'text-primary' : 'text-muted-foreground'}`}
                    >
                        Todos{' '}
                        <span className="text-muted-foreground">{total}</span>
                    </button>
                    {statuses.map((s) => (
                        <button
                            key={s.value}
                            onClick={() => filterBy(s.value)}
                            className={`ml-4 text-sm font-semibold ${filters.status === s.value ? 'text-primary' : 'text-muted-foreground'}`}
                        >
                            {s.label}{' '}
                            <span className="font-normal">
                                {counts[s.value] ?? 0}
                            </span>
                        </button>
                    ))}
                </div>

                {/* Tabela */}
                <div className="overflow-hidden rounded-xl border border-border bg-card">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-border text-left text-xs tracking-wide text-muted-foreground uppercase">
                                <th className="px-5 py-3 font-semibold">
                                    Título
                                </th>
                                <th className="px-5 py-3 font-semibold">
                                    Categoria
                                </th>
                                <th className="px-5 py-3 font-semibold">
                                    Autor
                                </th>
                                <th className="px-5 py-3 font-semibold">
                                    Estado
                                </th>
                                <th className="px-5 py-3 font-semibold">
                                    Actualizado
                                </th>
                                <th className="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {articles.data.map((a) => (
                                <tr
                                    key={a.id}
                                    className="border-b border-border last:border-0"
                                >
                                    <td className="px-5 py-3 font-medium">
                                        {a.title}
                                    </td>
                                    <td className="px-5 py-3">
                                        {a.category ? (
                                            <span className="inline-flex items-center gap-2 text-muted-foreground">
                                                <span
                                                    className="inline-block size-2"
                                                    style={{
                                                        background:
                                                            a.color ??
                                                            '#123b30',
                                                    }}
                                                />
                                                {a.category}
                                            </span>
                                        ) : (
                                            <span className="text-muted-foreground">
                                                —
                                            </span>
                                        )}
                                    </td>
                                    <td className="px-5 py-3 text-muted-foreground">
                                        {a.author}
                                    </td>
                                    <td className="px-5 py-3">
                                        <span
                                            className={`inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold ${statusClasses(a.status)}`}
                                        >
                                            {a.statusLabel}
                                        </span>
                                    </td>
                                    <td className="px-5 py-3 text-muted-foreground">
                                        {a.updated}
                                    </td>
                                    <td className="px-5 py-3">
                                        <div className="flex items-center justify-end gap-4">
                                            {a.canEdit && (
                                                <Link
                                                    href={`/artigos/${a.slug}/editar`}
                                                    className="inline-flex items-center gap-1 text-sm font-semibold text-primary"
                                                >
                                                    <Pencil className="size-3.5" />
                                                    Editar
                                                </Link>
                                            )}
                                            {a.canDelete && (
                                                <button
                                                    type="button"
                                                    onClick={() => {
                                                        if (
                                                            confirm(
                                                                `Eliminar "${a.title}"? Esta acção não pode ser anulada.`,
                                                            )
                                                        ) {
                                                            router.delete(
                                                                `/artigos/${a.slug}`,
                                                                {
                                                                    preserveScroll: true,
                                                                },
                                                            );
                                                        }
                                                    }}
                                                    className="inline-flex items-center gap-1 text-sm text-destructive"
                                                    aria-label="Eliminar"
                                                >
                                                    <Trash2 className="size-3.5" />
                                                </button>
                                            )}
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {articles.data.length === 0 && (
                                <tr>
                                    <td
                                        colSpan={6}
                                        className="px-5 py-10 text-center text-muted-foreground"
                                    >
                                        Sem artigos para este filtro.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Paginação */}
                <div className="flex flex-wrap gap-1">
                    {articles.links.map((l, i) => (
                        <Link
                            key={i}
                            href={l.url ?? '#'}
                            preserveScroll
                            className={`min-w-9 rounded-md border px-3 py-1.5 text-center text-sm ${
                                l.active
                                    ? 'border-primary bg-primary text-primary-foreground'
                                    : 'border-border text-muted-foreground'
                            } ${!l.url ? 'pointer-events-none opacity-50' : ''}`}
                            dangerouslySetInnerHTML={{ __html: l.label }}
                        />
                    ))}
                </div>
            </div>
        </>
    );
}

ArticlesIndex.layout = {
    breadcrumbs: [
        { title: 'Painel', href: dashboard() },
        { title: 'Artigos', href: '/artigos' },
    ],
};
