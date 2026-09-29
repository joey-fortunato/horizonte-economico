import { Head } from '@inertiajs/react';
import { dashboard } from '@/routes';

type Props = {
    metrics: {
        published: number;
        views: number;
        views30d: number;
        subscribers: number;
    };
    viewsPerDay: { label: string; count: number }[];
    topArticles: {
        title: string;
        views: number;
        category: string | null;
        color: string | null;
        author: string;
    }[];
    byCategory: { name: string; color: string; articles: number; views: number }[];
    byAuthor: { name: string; title: string | null; articles: number; views: number }[];
};

const nf = new Intl.NumberFormat('pt-PT');

export default function StatsIndex({
    metrics,
    viewsPerDay,
    topArticles,
    byCategory,
    byAuthor,
}: Props) {
    const maxDay = Math.max(1, ...viewsPerDay.map((d) => d.count));
    const maxCat = Math.max(1, ...byCategory.map((c) => c.views));

    const cards = [
        { label: 'Publicados', value: metrics.published },
        { label: 'Leituras (total)', value: metrics.views },
        { label: 'Leituras (30 dias)', value: metrics.views30d },
        { label: 'Subscritores', value: metrics.subscribers },
    ];

    return (
        <>
            <Head title="Estatísticas" />
            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <div>
                    <h1 className="font-serif text-2xl font-bold tracking-tight">
                        Estatísticas
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Audiência e desempenho editorial.
                    </p>
                </div>

                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {cards.map((c) => (
                        <div
                            key={c.label}
                            className="rounded-xl border border-border bg-card p-5"
                        >
                            <div className="text-sm text-muted-foreground">
                                {c.label}
                            </div>
                            <div className="mt-1 font-serif text-4xl font-bold">
                                {nf.format(c.value)}
                            </div>
                        </div>
                    ))}
                </div>

                <div className="rounded-xl border border-border bg-card p-5">
                    <div className="mb-5 font-semibold">
                        Leituras por dia
                        <span className="ml-2 text-xs font-normal text-muted-foreground">
                            últimos 30 dias
                        </span>
                    </div>
                    <div className="flex h-40 items-end gap-1">
                        {viewsPerDay.map((d, i) => (
                            <div
                                key={i}
                                className="flex flex-1 flex-col items-center justify-end"
                                title={`${d.label}: ${d.count}`}
                            >
                                <div
                                    className="w-full rounded-t bg-primary"
                                    style={{
                                        height: `${Math.max(2, (d.count / maxDay) * 100)}%`,
                                    }}
                                />
                            </div>
                        ))}
                    </div>
                    {metrics.views30d === 0 && (
                        <p className="mt-3 text-xs text-muted-foreground">
                            Ainda sem leituras registadas nos últimos 30 dias.
                        </p>
                    )}
                </div>

                <div className="grid gap-4 lg:grid-cols-2">
                    {/* Top artigos */}
                    <div className="overflow-hidden rounded-xl border border-border bg-card">
                        <div className="border-b border-border px-5 py-4 font-semibold">
                            Artigos mais lidos
                        </div>
                        <table className="w-full text-sm">
                            <tbody>
                                {topArticles.map((a, i) => (
                                    <tr
                                        key={i}
                                        className="border-b border-border last:border-0"
                                    >
                                        <td className="px-5 py-3">
                                            <div className="font-medium">
                                                {a.title}
                                            </div>
                                            <div className="text-xs text-muted-foreground">
                                                {a.category ?? '—'} · {a.author}
                                            </div>
                                        </td>
                                        <td className="px-5 py-3 text-right font-semibold tabular-nums">
                                            {nf.format(a.views)}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {/* Por categoria */}
                    <div className="rounded-xl border border-border bg-card p-5">
                        <div className="mb-4 font-semibold">Por categoria</div>
                        <div className="flex flex-col gap-3">
                            {byCategory.map((c, i) => (
                                <div key={i}>
                                    <div className="mb-1 flex items-center justify-between text-sm">
                                        <span className="flex items-center gap-2">
                                            <span
                                                className="inline-block size-2.5"
                                                style={{ background: c.color }}
                                            />
                                            {c.name}
                                        </span>
                                        <span className="text-muted-foreground tabular-nums">
                                            {nf.format(c.views)} · {c.articles} art.
                                        </span>
                                    </div>
                                    <div className="h-2 w-full rounded bg-muted">
                                        <div
                                            className="h-full rounded"
                                            style={{
                                                width: `${(c.views / maxCat) * 100}%`,
                                                background: c.color,
                                            }}
                                        />
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Por autor */}
                <div className="overflow-hidden rounded-xl border border-border bg-card">
                    <div className="border-b border-border px-5 py-4 font-semibold">
                        Por autor
                    </div>
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-border text-left text-xs uppercase tracking-wide text-muted-foreground">
                                <th className="px-5 py-3 font-semibold">Autor</th>
                                <th className="px-5 py-3 font-semibold">Artigos</th>
                                <th className="px-5 py-3 text-right font-semibold">
                                    Leituras
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {byAuthor.map((a, i) => (
                                <tr
                                    key={i}
                                    className="border-b border-border last:border-0"
                                >
                                    <td className="px-5 py-3">
                                        <div className="font-medium">{a.name}</div>
                                        {a.title && (
                                            <div className="text-xs text-muted-foreground">
                                                {a.title}
                                            </div>
                                        )}
                                    </td>
                                    <td className="px-5 py-3 tabular-nums">
                                        {a.articles}
                                    </td>
                                    <td className="px-5 py-3 text-right font-semibold tabular-nums">
                                        {nf.format(a.views)}
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

StatsIndex.layout = {
    breadcrumbs: [
        { title: 'Painel', href: dashboard() },
        { title: 'Estatísticas', href: '/estatisticas' },
    ],
};
