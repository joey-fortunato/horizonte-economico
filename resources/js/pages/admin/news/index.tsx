import { Head, Link, router } from '@inertiajs/react';
import { ExternalLink, RefreshCw, History } from 'lucide-react';
import { dashboard } from '@/routes';

type Row = {
    id: number;
    title: string;
    url: string;
    source: string | null;
    source_name: string | null;
    published_at: string | null;
    fetched_at: string;
    status: string;
    statusLabel: string;
};

type Props = {
    news: {
        data: Row[];
        links: { url: string | null; label: string; active: boolean }[];
    };
    filters: Record<string, string | null>;
    counts: Record<string, number>;
    statuses: { value: string; label: string }[];
    sources: { id: number; name: string }[];
    canDecide: boolean;
    canCollect: boolean;
};

function statusClasses(status: string): string {
    switch (status) {
        case 'selected':
            return 'bg-primary/10 text-primary';
        case 'rejected':
            return 'bg-destructive/10 text-destructive';
        case 'drafting':
            return 'bg-[#f4ead6] text-[#7a5a12]';
        case 'done':
            return 'bg-muted text-muted-foreground';
        default:
            return 'bg-secondary text-secondary-foreground';
    }
}

export default function NewsIndex({
    news,
    filters,
    counts,
    statuses,
    sources,
    canCollect,
}: Props) {
    const total = Object.values(counts).reduce((a, b) => a + b, 0);

    const apply = (patch: Record<string, string | null>) => {
        router.get('/noticias', { ...filters, ...patch }, { preserveState: true, replace: true });
    };

    return (
        <>
            <Head title="Central de Notícias" />
            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <div className="flex flex-wrap items-center gap-4">
                    <div>
                        <h1 className="font-serif text-2xl font-bold tracking-tight">
                            Central de Notícias
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            {total} notícias recolhidas
                        </p>
                    </div>
                    <div className="ml-auto flex items-center gap-3">
                        <Link
                            href="/noticias/historico"
                            className="inline-flex items-center gap-2 rounded-md border border-border px-3 py-2 text-sm font-semibold text-muted-foreground"
                        >
                            <History className="size-4" />
                            Histórico
                        </Link>
                        {canCollect && (
                            <button
                                type="button"
                                onClick={() =>
                                    router.post('/noticias/recolher', {}, { preserveScroll: true })
                                }
                                className="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground"
                            >
                                <RefreshCw className="size-4" />
                                Recolher agora
                            </button>
                        )}
                    </div>
                </div>

                {/* Filtros */}
                <div className="flex flex-wrap items-center gap-3 border-b border-border pb-3">
                    <button
                        onClick={() => apply({ status: null, unanalyzed: null })}
                        className={`text-sm font-semibold ${!filters.status ? 'text-primary' : 'text-muted-foreground'}`}
                    >
                        Todas <span className="text-muted-foreground">{total}</span>
                    </button>
                    {statuses.map((s) => (
                        <button
                            key={s.value}
                            onClick={() => apply({ status: s.value, unanalyzed: null })}
                            className={`text-sm font-semibold ${filters.status === s.value ? 'text-primary' : 'text-muted-foreground'}`}
                        >
                            {s.label}{' '}
                            <span className="font-normal">{counts[s.value] ?? 0}</span>
                        </button>
                    ))}
                    <div className="ml-auto flex items-center gap-2">
                        <select
                            value={filters.source ?? ''}
                            onChange={(e) => apply({ source: e.target.value || null })}
                            className="rounded-md border border-input bg-background px-2 py-1.5 text-sm"
                        >
                            <option value="">Todas as fontes</option>
                            {sources.map((s) => (
                                <option key={s.id} value={s.id}>
                                    {s.name}
                                </option>
                            ))}
                        </select>
                        <input
                            defaultValue={filters.q ?? ''}
                            onKeyDown={(e) => {
                                if (e.key === 'Enter')
                                    apply({ q: (e.target as HTMLInputElement).value || null });
                            }}
                            placeholder="Pesquisar título…"
                            className="rounded-md border border-input bg-background px-3 py-1.5 text-sm"
                        />
                    </div>
                </div>

                {/* Tabela */}
                <div className="overflow-hidden rounded-xl border border-border bg-card">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-border text-left text-xs uppercase tracking-wide text-muted-foreground">
                                <th className="px-5 py-3 font-semibold">Título</th>
                                <th className="px-5 py-3 font-semibold">Fonte</th>
                                <th className="px-5 py-3 font-semibold">Publicado</th>
                                <th className="px-5 py-3 font-semibold">Recolhido</th>
                                <th className="px-5 py-3 font-semibold">Estado</th>
                                <th className="px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            {news.data.map((n) => (
                                <tr key={n.id} className="border-b border-border last:border-0">
                                    <td className="px-5 py-3">
                                        <Link
                                            href={`/noticias/${n.id}`}
                                            className="font-medium hover:text-primary"
                                        >
                                            {n.title}
                                        </Link>
                                    </td>
                                    <td className="px-5 py-3 text-muted-foreground">
                                        {n.source_name ?? n.source ?? '—'}
                                    </td>
                                    <td className="px-5 py-3 text-muted-foreground">
                                        {n.published_at ?? '—'}
                                    </td>
                                    <td className="px-5 py-3 text-muted-foreground">
                                        {n.fetched_at}
                                    </td>
                                    <td className="px-5 py-3">
                                        <span
                                            className={`inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold ${statusClasses(n.status)}`}
                                        >
                                            {n.statusLabel}
                                        </span>
                                    </td>
                                    <td className="px-5 py-3 text-right">
                                        <a
                                            href={n.url}
                                            target="_blank"
                                            rel="noopener"
                                            className="inline-flex items-center gap-1 text-sm text-muted-foreground hover:text-primary"
                                        >
                                            <ExternalLink className="size-3.5" />
                                        </a>
                                    </td>
                                </tr>
                            ))}
                            {news.data.length === 0 && (
                                <tr>
                                    <td colSpan={6} className="px-5 py-10 text-center text-muted-foreground">
                                        Sem notícias para este filtro. Use "Recolher agora" para actualizar.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                <div className="flex flex-wrap gap-1">
                    {news.links.map((l, i) => (
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

NewsIndex.layout = {
    breadcrumbs: [
        { title: 'Painel', href: dashboard() },
        { title: 'Central de Notícias', href: '/noticias' },
    ],
};
