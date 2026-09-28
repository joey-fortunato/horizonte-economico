import { Head } from '@inertiajs/react';
import {
    CalendarClock,
    Check,
    Eye,
    PencilLine,
    Plus,
    Search,
} from 'lucide-react';
import { dashboard } from '@/routes';

type Metrics = {
    published: number;
    draft: number;
    scheduled: number;
    review: number;
    views: number;
};

type Props = {
    metrics: Metrics;
    perDay: { label: string; count: number }[];
    mostRead: { title: string; views: number; category: string | null; color: string }[];
    recent: { title: string; author: string; status: string; statusLabel: string; updated: string }[];
};

const nf = new Intl.NumberFormat('pt-PT');

function statusClasses(status: string): string {
    switch (status) {
        case 'published':
            return 'bg-primary/10 text-primary';
        case 'scheduled':
            return 'bg-[#f4ead6] text-[#7a5a12]';
        case 'review':
            return 'bg-destructive/10 text-destructive';
        default:
            return 'bg-muted text-muted-foreground';
    }
}

export default function Dashboard({
    metrics,
    perDay,
    mostRead,
    recent,
}: Props) {
    const maxDay = Math.max(1, ...perDay.map((d) => d.count));

    const cards = [
        { label: 'Publicados', value: metrics.published, icon: Check, tint: 'bg-primary/10 text-primary', note: 'Visíveis ao público' },
        { label: 'Rascunhos', value: metrics.draft, icon: PencilLine, tint: 'bg-muted text-muted-foreground', note: `${metrics.review} em revisão` },
        { label: 'Agendados', value: metrics.scheduled, icon: CalendarClock, tint: 'bg-[#f4ead6] text-[#7a5a12]', note: 'Publicação automática' },
        { label: 'Leituras (total)', value: metrics.views, icon: Eye, tint: 'bg-primary/10 text-primary', note: 'Somatório de visualizações' },
    ];

    return (
        <>
            <Head title="Painel" />

            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                {/* Cabeçalho */}
                <div className="flex flex-wrap items-center gap-4">
                    <div>
                        <h1 className="font-serif text-2xl font-bold tracking-tight">
                            Painel
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            Resumo editorial do Horizonte Económico.
                        </p>
                    </div>
                    <div className="ml-auto flex items-center gap-3">
                        <div className="hidden items-center gap-2 rounded-md border border-border px-3 py-2 text-sm text-muted-foreground sm:flex">
                            <Search className="size-4" />
                            Pesquisar…
                        </div>
                        <a
                            href="#"
                            className="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground"
                        >
                            <Plus className="size-4" />
                            Novo artigo
                        </a>
                    </div>
                </div>

                {/* Cartões de métricas */}
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {cards.map((c) => (
                        <div
                            key={c.label}
                            className="rounded-xl border border-border bg-card p-5"
                        >
                            <div className="flex items-start justify-between">
                                <div>
                                    <div className="text-sm text-muted-foreground">
                                        {c.label}
                                    </div>
                                    <div className="mt-1 font-serif text-4xl font-bold">
                                        {nf.format(c.value)}
                                    </div>
                                </div>
                                <span
                                    className={`flex size-10 items-center justify-center rounded-lg ${c.tint}`}
                                >
                                    <c.icon className="size-5" />
                                </span>
                            </div>
                            <div className="mt-2 text-xs text-muted-foreground">
                                {c.note}
                            </div>
                        </div>
                    ))}
                </div>

                {/* Gráfico + Mais lidos */}
                <div className="grid gap-4 lg:grid-cols-[1.6fr_1fr]">
                    <div className="rounded-xl border border-border bg-card p-5">
                        <div className="mb-5 font-semibold">
                            Publicações por dia
                            <span className="ml-2 text-xs font-normal text-muted-foreground">
                                últimos 7 dias
                            </span>
                        </div>
                        <div className="flex h-44 items-end gap-3">
                            {perDay.map((d, i) => (
                                <div
                                    key={i}
                                    className="flex flex-1 flex-col items-center gap-2"
                                >
                                    <div className="flex h-full w-full items-end">
                                        <div
                                            className="w-full rounded-t bg-primary"
                                            style={{
                                                height: `${Math.max(6, (d.count / maxDay) * 100)}%`,
                                            }}
                                        />
                                    </div>
                                    <span className="text-xs text-muted-foreground">
                                        {d.label}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </div>

                    <div className="rounded-xl border border-border bg-card p-5">
                        <div className="mb-4 font-semibold">
                            Artigos mais lidos
                        </div>
                        <div className="flex flex-col gap-4">
                            {mostRead.map((a, i) => (
                                <div key={i} className="flex items-center gap-3">
                                    <span
                                        className="font-serif text-xl font-semibold"
                                        style={{ color: a.color }}
                                    >
                                        {i + 1}
                                    </span>
                                    <div className="min-w-0 flex-1">
                                        <div className="truncate text-sm font-medium">
                                            {a.title}
                                        </div>
                                        <div className="text-xs text-muted-foreground">
                                            {nf.format(a.views)} leituras
                                            {a.category ? ` · ${a.category}` : ''}
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Recentes */}
                <div className="overflow-hidden rounded-xl border border-border bg-card">
                    <div className="flex items-center justify-between border-b border-border px-5 py-4">
                        <div className="font-semibold">
                            Actualizados recentemente
                        </div>
                        <a
                            href="#"
                            className="text-sm font-semibold text-primary"
                        >
                            Ver todos →
                        </a>
                    </div>
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-border text-left text-xs uppercase tracking-wide text-muted-foreground">
                                <th className="px-5 py-3 font-semibold">
                                    Título
                                </th>
                                <th className="px-5 py-3 font-semibold">Autor</th>
                                <th className="px-5 py-3 font-semibold">
                                    Estado
                                </th>
                                <th className="px-5 py-3 font-semibold">
                                    Actualizado
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {recent.map((r, i) => (
                                <tr
                                    key={i}
                                    className="border-b border-border last:border-0"
                                >
                                    <td className="px-5 py-3 font-medium">
                                        {r.title}
                                    </td>
                                    <td className="px-5 py-3 text-muted-foreground">
                                        {r.author}
                                    </td>
                                    <td className="px-5 py-3">
                                        <span
                                            className={`inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold ${statusClasses(r.status)}`}
                                        >
                                            {r.statusLabel}
                                        </span>
                                    </td>
                                    <td className="px-5 py-3 text-muted-foreground">
                                        {r.updated}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Painel',
            href: dashboard(),
        },
    ],
};
