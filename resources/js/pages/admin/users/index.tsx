import { Head, router, useForm } from '@inertiajs/react';
import { Trash2 } from 'lucide-react';
import { dashboard } from '@/routes';

type Row = {
    id: number;
    name: string;
    email: string;
    role: string;
    roleLabel: string;
    status: string;
    title: string | null;
    articles: number;
    isSelf: boolean;
};

type RoleOpt = { value: string; label: string };

type Props = {
    users: Row[];
    roles: RoleOpt[];
};

const field =
    'w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:border-ring focus:outline-none';

function InviteForm({ roles }: { roles: RoleOpt[] }) {
    const form = useForm({ name: '', email: '', role: 'author', title: '' });

    return (
        <form
            onSubmit={(e) => {
                e.preventDefault();
                form.post('/utilizadores', { onSuccess: () => form.reset() });
            }}
            className="rounded-xl border border-border bg-card p-5"
        >
            <div className="mb-3 font-semibold">Convidar utilizador</div>
            <input
                className={field}
                placeholder="Nome"
                value={form.data.name}
                onChange={(e) => form.setData('name', e.target.value)}
            />
            <input
                className={`${field} mt-3`}
                placeholder="Email"
                type="email"
                value={form.data.email}
                onChange={(e) => form.setData('email', e.target.value)}
            />
            <input
                className={`${field} mt-3`}
                placeholder="Cargo (opcional)"
                value={form.data.title}
                onChange={(e) => form.setData('title', e.target.value)}
            />
            <select
                className={`${field} mt-3`}
                value={form.data.role}
                onChange={(e) => form.setData('role', e.target.value)}
            >
                {roles.map((r) => (
                    <option key={r.value} value={r.value}>
                        {r.label}
                    </option>
                ))}
            </select>
            <button
                type="submit"
                disabled={form.processing}
                className="mt-3 w-full rounded-md bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground"
            >
                Enviar convite
            </button>
        </form>
    );
}

function UserRow({ user, roles }: { user: Row; roles: RoleOpt[] }) {
    const form = useForm({ role: user.role, status: user.status });

    return (
        <tr className="border-b border-border last:border-0">
            <td className="px-4 py-3">
                <div className="font-medium">{user.name}</div>
                <div className="text-xs text-muted-foreground">
                    {user.email}
                </div>
            </td>
            <td className="px-4 py-3">
                <select
                    className="rounded-md border border-input bg-background px-2 py-1 text-sm"
                    value={form.data.role}
                    onChange={(e) => form.setData('role', e.target.value)}
                >
                    {roles.map((r) => (
                        <option key={r.value} value={r.value}>
                            {r.label}
                        </option>
                    ))}
                </select>
            </td>
            <td className="px-4 py-3">
                <select
                    className="rounded-md border border-input bg-background px-2 py-1 text-sm"
                    value={form.data.status}
                    onChange={(e) => form.setData('status', e.target.value)}
                >
                    <option value="active">Activo</option>
                    <option value="invited">Convite pendente</option>
                    <option value="disabled">Desactivado</option>
                </select>
            </td>
            <td className="px-4 py-3 text-muted-foreground">{user.articles}</td>
            <td className="px-4 py-3 text-right">
                <button
                    onClick={() => {
                        form.transform((d) => ({ ...d, _method: 'put' }));
                        form.post(`/utilizadores/${user.id}`);
                    }}
                    className="mr-3 text-sm font-semibold text-primary"
                >
                    Guardar
                </button>
                {!user.isSelf && (
                    <button
                        onClick={() =>
                            router.delete(`/utilizadores/${user.id}`)
                        }
                        className="text-destructive"
                        aria-label="Remover"
                    >
                        <Trash2 className="inline size-4" />
                    </button>
                )}
            </td>
        </tr>
    );
}

export default function UsersIndex({ users, roles }: Props) {
    return (
        <>
            <Head title="Utilizadores" />
            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <h1 className="font-serif text-2xl font-bold tracking-tight">
                    Utilizadores e permissões
                </h1>

                <div className="grid gap-6 lg:grid-cols-[1fr_320px]">
                    <div className="overflow-hidden rounded-xl border border-border bg-card">
                        <table className="w-full text-sm">
                            <thead>
                                <tr className="border-b border-border text-left text-xs tracking-wide text-muted-foreground uppercase">
                                    <th className="px-4 py-3 font-semibold">
                                        Utilizador
                                    </th>
                                    <th className="px-4 py-3 font-semibold">
                                        Perfil
                                    </th>
                                    <th className="px-4 py-3 font-semibold">
                                        Estado
                                    </th>
                                    <th className="px-4 py-3 font-semibold">
                                        Artigos
                                    </th>
                                    <th className="px-4 py-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                {users.map((u) => (
                                    <UserRow
                                        key={u.id}
                                        user={u}
                                        roles={roles}
                                    />
                                ))}
                            </tbody>
                        </table>
                    </div>
                    <InviteForm roles={roles} />
                </div>
            </div>
        </>
    );
}

UsersIndex.layout = {
    breadcrumbs: [
        { title: 'Painel', href: dashboard() },
        { title: 'Utilizadores', href: '/utilizadores' },
    ],
};
