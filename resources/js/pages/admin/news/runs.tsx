import { Head, Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import { dashboard } from '@/routes';

type Run = {
    id: number;
    source: string;
    status: string;
    statusLabel: string;
    started_at: string | null;
    items_found: number;
    items_created: number;
    error: string | null;
};

type Props = {
    runs: {
        data: Run[];
        links: { url: string | null; label: string; active: boolean }[];
    };
};

function statusClasses(status: string): string {
    switch (status) {
        case 'completed':
            return 'bg-primary/10 text-primary';
        case 'completed_with_errors':
            return 'bg-[#f4ead6] text-[#7a5a12]';
        case 'failed':
            return 'bg-destructive/10 text-destructive';
        default:
            return 'bg-secondary text-secondary-foreground';
    }
}

export default function NewsRuns({ runs }: Props) {
    return (
        <>
            <Head title="Histórico de recolhas" />
            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <Link
                    href="/noticias"
                    className="flex w-fit items-center gap-2 text-sm font-semibold text-muted-foreground"
                >
                    <ArrowLeft className="size-4" />
                    Central de Notícias
                </Link>
                <h1 className="font-serif text-2xl font-bold tracking-tight">
                    Histórico de recolhas
                </h1>

                <div className="overflow-hidden rounded-xl border border-border bg-card">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-border text-left text-xs uppercase tracking-wide text-muted-foreground">
                                <th className="px-5 py-3 font-semibold">Fonte</th>
                                <th className="px-5 py-3 font-semibold">Início</th>
                                <th className="px-5 py-3 font-semibold">Estado</th>
                                <th className="px-5 py-3 font-semibold">Encontrados</th>
                                <th className="px-5 py-3 font-semibold">Novos</th>
                                <th className="px-5 py-3 font-semibold">Erro</th>
                            </tr>
                        </thead>
                        <tbody>
                            {runs.data.map((r) => (
                                <tr key={r.id} className="border-b border-border last:border-0">
                                    <td className="px-5 py-3 font-medium">{r.source}</td>
                                    <td className="px-5 py-3 text-muted-foreground">
                                        {r.started_at ?? '—'}
                                    </td>
                                    <td className="px-5 py-3">
                                        <span
                                            className={`inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold ${statusClasses(r.status)}`}
                                        >
                                            {r.statusLabel}
                                        </span>
                                    </td>
                                    <td className="px-5 py-3 tabular-nums">{r.items_found}</td>
                                    <td className="px-5 py-3 tabular-nums">{r.items_created}</td>
                                    <td className="max-w-[280px] truncate px-5 py-3 text-xs text-destructive">
                                        {r.error ?? ''}
                                    </td>
                                </tr>
                            ))}
                            {runs.data.length === 0 && (
                                <tr>
                                    <td colSpan={6} className="px-5 py-10 text-center text-muted-foreground">
                                        Sem execuções registadas.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                <div className="flex flex-wrap gap-1">
                    {runs.links.map((l, i) => (
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

NewsRuns.layout = {
    breadcrumbs: [
        { title: 'Painel', href: dashboard() },
        { title: 'Central de Notícias', href: '/noticias' },
    ],
};
