import { Head, useForm } from '@inertiajs/react';
import { Trash2 } from 'lucide-react';
import { dashboard } from '@/routes';

type Category = {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    color: string;
    is_active: boolean;
    articles_count: number;
};

type Props = {
    categories: Category[];
    canManage: boolean;
};

const field =
    'w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:border-ring focus:outline-none';

function CreateForm() {
    const form = useForm({
        name: '',
        description: '',
        color: '#123b30',
        is_active: true,
    });

    return (
        <form
            onSubmit={(e) => {
                e.preventDefault();
                form.post('/categorias', { onSuccess: () => form.reset() });
            }}
            className="rounded-xl border border-border bg-card p-5"
        >
            <div className="mb-3 font-semibold">Nova categoria</div>
            <input
                className={field}
                placeholder="Nome"
                value={form.data.name}
                onChange={(e) => form.setData('name', e.target.value)}
            />
            <input
                className={`${field} mt-3`}
                placeholder="Descrição"
                value={form.data.description}
                onChange={(e) => form.setData('description', e.target.value)}
            />
            <div className="mt-3 flex items-center gap-3">
                <input
                    type="color"
                    value={form.data.color}
                    onChange={(e) => form.setData('color', e.target.value)}
                    className="h-9 w-12 rounded border border-input"
                />
                <label className="flex items-center gap-2 text-sm">
                    <input
                        type="checkbox"
                        checked={form.data.is_active}
                        onChange={(e) =>
                            form.setData('is_active', e.target.checked)
                        }
                    />
                    Activa
                </label>
                <button
                    type="submit"
                    disabled={form.processing}
                    className="ml-auto rounded-md bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground"
                >
                    Criar
                </button>
            </div>
        </form>
    );
}

function Row({ category }: { category: Category }) {
    const form = useForm({
        name: category.name,
        description: category.description ?? '',
        color: category.color,
        is_active: category.is_active,
    });

    return (
        <tr className="border-b border-border last:border-0">
            <td className="px-4 py-3">
                <div className="flex items-center gap-3">
                    <input
                        type="color"
                        value={form.data.color}
                        onChange={(e) => form.setData('color', e.target.value)}
                        className="h-7 w-9 rounded border border-input"
                    />
                    <input
                        className="rounded-md border border-input bg-background px-2 py-1 text-sm"
                        value={form.data.name}
                        onChange={(e) => form.setData('name', e.target.value)}
                    />
                </div>
            </td>
            <td className="px-4 py-3 text-muted-foreground">
                /{category.slug}
            </td>
            <td className="px-4 py-3 text-muted-foreground">
                {category.articles_count}
            </td>
            <td className="px-4 py-3">
                <label className="flex items-center gap-2 text-sm">
                    <input
                        type="checkbox"
                        checked={form.data.is_active}
                        onChange={(e) =>
                            form.setData('is_active', e.target.checked)
                        }
                    />
                    {form.data.is_active ? 'Activa' : 'Inactiva'}
                </label>
            </td>
            <td className="px-4 py-3 text-right">
                <button
                    onClick={() => {
                        form.transform((d) => ({ ...d, _method: 'put' }));
                        form.post(`/categorias/${category.slug}`);
                    }}
                    className="mr-3 text-sm font-semibold text-primary"
                >
                    Guardar
                </button>
                <button
                    onClick={() => form.delete(`/categorias/${category.slug}`)}
                    className="text-sm text-destructive"
                    aria-label="Eliminar"
                >
                    <Trash2 className="inline size-4" />
                </button>
            </td>
        </tr>
    );
}

export default function CategoriesIndex({ categories }: Props) {
    return (
        <>
            <Head title="Categorias" />
            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <h1 className="font-serif text-2xl font-bold tracking-tight">
                    Categorias
                </h1>

                <div className="grid gap-6 lg:grid-cols-[1fr_320px]">
                    <div className="overflow-hidden rounded-xl border border-border bg-card">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="border-b border-border text-left text-xs tracking-wide text-muted-foreground uppercase">
                                    <th className="px-4 py-3 font-semibold">
                                        Categoria
                                    </th>
                                    <th className="px-4 py-3 font-semibold">
                                        Slug
                                    </th>
                                    <th className="px-4 py-3 font-semibold">
                                        Artigos
                                    </th>
                                    <th className="px-4 py-3 font-semibold">
                                        Estado
                                    </th>
                                    <th className="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                {categories.map((c) => (
                                    <Row key={c.id} category={c} />
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <CreateForm />
                </div>
            </div>
        </>
    );
}

CategoriesIndex.layout = {
    breadcrumbs: [
        { title: 'Painel', href: dashboard() },
        { title: 'Categorias', href: '/categorias' },
    ],
};
