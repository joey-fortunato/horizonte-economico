import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import { type FormEvent } from 'react';
import InputError from '@/components/input-error';
import { dashboard } from '@/routes';

type ArticleData = {
    id: number | null;
    title: string;
    slug: string | null;
    excerpt: string | null;
    body: string | null;
    category_id: number | null;
    status: string;
    published_at: string | null;
    seo_title: string | null;
    seo_description: string | null;
    correction_note: string | null;
    tags: number[];
    cover_url: string | null;
};

type Props = {
    article: ArticleData;
    categories: { id: number; name: string; color: string }[];
    allTags: { id: number; name: string }[];
    statuses: { value: string; label: string }[];
    canPublish: boolean;
};

export default function ArticleEdit({
    article,
    categories,
    allTags,
    statuses,
    canPublish,
}: Props) {
    const isEdit = !!article.id;

    const form = useForm({
        title: article.title ?? '',
        slug: article.slug ?? '',
        excerpt: article.excerpt ?? '',
        body: article.body ?? '',
        category_id: article.category_id ?? '',
        status: article.status ?? 'draft',
        published_at: article.published_at ?? '',
        seo_title: article.seo_title ?? '',
        seo_description: article.seo_description ?? '',
        correction_note: article.correction_note ?? '',
        tags: article.tags ?? [],
        cover: null as File | null,
    });

    const availableStatuses = canPublish
        ? statuses
        : statuses.filter((s) => ['draft', 'review'].includes(s.value));

    const submit = (e: FormEvent) => {
        e.preventDefault();
        if (isEdit) {
            form.transform((data) => ({ ...data, _method: 'put' }));
            form.post(`/artigos/${article.slug}`, { forceFormData: true });
        } else {
            form.post('/artigos', { forceFormData: true });
        }
    };

    const toggleTag = (id: number) => {
        const has = form.data.tags.includes(id);
        form.setData(
            'tags',
            has
                ? form.data.tags.filter((t) => t !== id)
                : [...form.data.tags, id],
        );
    };

    const field =
        'w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:border-ring focus:outline-none';
    const label = 'mb-1.5 block text-xs font-semibold uppercase tracking-wide text-muted-foreground';

    return (
        <>
            <Head title={isEdit ? 'Editar artigo' : 'Novo artigo'} />

            <form
                onSubmit={submit}
                className="flex h-full flex-1 flex-col gap-6 p-4"
            >
                <div className="flex flex-wrap items-center gap-4">
                    <Link
                        href="/artigos"
                        className="flex items-center gap-2 text-sm font-semibold text-muted-foreground"
                    >
                        <ArrowLeft className="size-4" />
                        Artigos
                    </Link>
                    <h1 className="font-serif text-2xl font-bold tracking-tight">
                        {isEdit ? 'Editar artigo' : 'Novo artigo'}
                    </h1>
                    <button
                        type="submit"
                        disabled={form.processing}
                        className="ml-auto rounded-md bg-primary px-5 py-2 text-sm font-semibold text-primary-foreground disabled:opacity-60"
                    >
                        Guardar
                    </button>
                </div>

                <div className="grid gap-6 lg:grid-cols-[1fr_320px]">
                    {/* Coluna principal */}
                    <div className="flex flex-col gap-5">
                        <div className="rounded-xl border border-border bg-card p-5">
                            <label className={label}>Título</label>
                            <input
                                className={field}
                                value={form.data.title}
                                onChange={(e) => form.setData('title', e.target.value)}
                            />
                            <InputError message={form.errors.title} className="mt-1" />

                            <label className={`${label} mt-4`}>Resumo</label>
                            <textarea
                                rows={2}
                                className={field}
                                value={form.data.excerpt}
                                onChange={(e) => form.setData('excerpt', e.target.value)}
                            />
                            <InputError message={form.errors.excerpt} className="mt-1" />

                            <label className={`${label} mt-4`}>
                                Corpo do artigo (HTML)
                            </label>
                            <textarea
                                rows={16}
                                className={`${field} font-mono text-[13px] leading-relaxed`}
                                value={form.data.body}
                                onChange={(e) => form.setData('body', e.target.value)}
                            />
                            <InputError message={form.errors.body} className="mt-1" />
                        </div>

                        <div className="rounded-xl border border-border bg-card p-5">
                            <div className="mb-3 font-semibold">SEO</div>
                            <label className={label}>Título SEO</label>
                            <input
                                className={field}
                                value={form.data.seo_title}
                                onChange={(e) => form.setData('seo_title', e.target.value)}
                            />
                            <label className={`${label} mt-4`}>Descrição SEO</label>
                            <textarea
                                rows={2}
                                className={field}
                                value={form.data.seo_description}
                                onChange={(e) => form.setData('seo_description', e.target.value)}
                            />
                        </div>
                    </div>

                    {/* Barra lateral */}
                    <div className="flex flex-col gap-5">
                        <div className="rounded-xl border border-border bg-card p-5">
                            <label className={label}>Estado</label>
                            <select
                                className={field}
                                value={form.data.status}
                                onChange={(e) => form.setData('status', e.target.value)}
                            >
                                {availableStatuses.map((s) => (
                                    <option key={s.value} value={s.value}>
                                        {s.label}
                                    </option>
                                ))}
                            </select>
                            <InputError message={form.errors.status} className="mt-1" />

                            {form.data.status === 'scheduled' && (
                                <>
                                    <label className={`${label} mt-4`}>
                                        Data de publicação
                                    </label>
                                    <input
                                        type="datetime-local"
                                        className={field}
                                        value={form.data.published_at ?? ''}
                                        onChange={(e) => form.setData('published_at', e.target.value)}
                                    />
                                </>
                            )}
                        </div>

                        <div className="rounded-xl border border-border bg-card p-5">
                            <label className={label}>Categoria</label>
                            <select
                                className={field}
                                value={form.data.category_id}
                                onChange={(e) =>
                                    form.setData(
                                        'category_id',
                                        e.target.value ? Number(e.target.value) : '',
                                    )
                                }
                            >
                                <option value="">— Selecionar —</option>
                                {categories.map((c) => (
                                    <option key={c.id} value={c.id}>
                                        {c.name}
                                    </option>
                                ))}
                            </select>

                            <label className={`${label} mt-4`}>Etiquetas</label>
                            <div className="flex flex-wrap gap-2">
                                {allTags.map((t) => {
                                    const on = form.data.tags.includes(t.id);
                                    return (
                                        <button
                                            type="button"
                                            key={t.id}
                                            onClick={() => toggleTag(t.id)}
                                            className={`rounded-full border px-3 py-1 text-xs font-semibold ${
                                                on
                                                    ? 'border-primary bg-primary/10 text-primary'
                                                    : 'border-border text-muted-foreground'
                                            }`}
                                        >
                                            {t.name}
                                        </button>
                                    );
                                })}
                            </div>
                        </div>

                        <div className="rounded-xl border border-border bg-card p-5">
                            <label className={label}>Imagem de capa</label>
                            {form.data.cover ? (
                                <p className="mb-2 text-xs text-muted-foreground">
                                    {form.data.cover.name}
                                </p>
                            ) : article.cover_url ? (
                                <img
                                    src={article.cover_url}
                                    alt=""
                                    className="mb-2 aspect-video w-full rounded-md object-cover"
                                />
                            ) : (
                                <div className="mb-2 flex aspect-video w-full items-center justify-center rounded-md bg-muted text-xs text-muted-foreground">
                                    Sem imagem
                                </div>
                            )}
                            <input
                                type="file"
                                accept="image/*"
                                onChange={(e) =>
                                    form.setData('cover', e.target.files?.[0] ?? null)
                                }
                                className="text-sm"
                            />
                            <InputError message={form.errors.cover} className="mt-1" />
                        </div>

                        <div className="rounded-xl border border-border bg-card p-5">
                            <label className={label}>Nota de correcção</label>
                            <textarea
                                rows={3}
                                className={field}
                                value={form.data.correction_note}
                                onChange={(e) =>
                                    form.setData('correction_note', e.target.value)
                                }
                            />
                        </div>
                    </div>
                </div>
            </form>
        </>
    );
}

ArticleEdit.layout = {
    breadcrumbs: [
        { title: 'Painel', href: dashboard() },
        { title: 'Artigos', href: '/artigos' },
    ],
};
