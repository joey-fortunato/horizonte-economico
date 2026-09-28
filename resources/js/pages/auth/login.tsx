import { Form, Head } from '@inertiajs/react';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
/* @chisel-registration */
import { register } from '@/routes';
/* @end-chisel-registration */
import { store } from '@/routes/login';
import { request } from '@/routes/password';
/* @chisel-passkeys */
import PasskeyVerify from '@/components/passkey-verify';
/* @end-chisel-passkeys */

type Props = {
    status?: string;
    canResetPassword: boolean;
};

export default function Login({ status, canResetPassword }: Props) {
    return (
        <>
            <Head title="Iniciar sessão" />

            {/* @chisel-passkeys */}
            <PasskeyVerify />
            {/* @end-chisel-passkeys */}

            <Form
                {...store.form()}
                resetOnSuccess={['password']}
                className="flex flex-col gap-6"
            >
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-6">
                            <div className="grid gap-2">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    autoFocus
                                    tabIndex={1}
                                    autoComplete="email"
                                    placeholder="editor@horizonteeconomico.com"
                                />
                                <InputError message={errors.email} />
                            </div>

                            <div className="grid gap-2">
                                <div className="flex items-center">
                                    <Label htmlFor="password">Palavra-passe</Label>
                                    {canResetPassword && (
                                        <TextLink
                                            href={request()}
                                            className="ml-auto text-sm"
                                            tabIndex={5}
                                        >
                                            Esqueceu-se?
                                        </TextLink>
                                    )}
                                </div>
                                <PasswordInput
                                    id="password"
                                    name="password"
                                    required
                                    tabIndex={2}
                                    autoComplete="current-password"
                                    placeholder="Palavra-passe"
                                />
                                <InputError message={errors.password} />
                            </div>

                            <div className="flex items-center space-x-3">
                                <Checkbox
                                    id="remember"
                                    name="remember"
                                    tabIndex={3}
                                />
                                <Label htmlFor="remember">
                                    Manter sessão iniciada
                                </Label>
                            </div>

                            <Button
                                type="submit"
                                className="mt-4 w-full"
                                tabIndex={4}
                                disabled={processing}
                                data-test="login-button"
                            >
                                {processing && <Spinner />}
                                Entrar no painel
                            </Button>
                        </div>

                        {/* @chisel-registration */}
                        <div className="text-center text-sm text-muted-foreground">
                            Ainda não tem conta?{' '}
                            <TextLink href={register()} tabIndex={5}>
                                Criar conta
                            </TextLink>
                        </div>
                        {/* @end-chisel-registration */}
                    </>
                )}
            </Form>

            {/* Aviso de segurança (área restrita) */}
            <div className="mt-6 flex items-start gap-3 border-l-2 border-[#d4ad67] bg-[#faf3e2] p-4 text-[12.5px] leading-relaxed text-[#7a5a1e]">
                <svg
                    width="22"
                    height="22"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="#9a7017"
                    strokeWidth="1.8"
                    className="mt-0.5 shrink-0"
                >
                    <path d="M12 2 4 6v6c0 5 4 8 8 10 4-2 8-5 8-10V6z" />
                </svg>
                Área protegida. As tentativas de acesso são registadas e
                limitadas por segurança.
            </div>

            {status && (
                <div className="mb-4 text-center text-sm font-medium text-green-600">
                    {status}
                </div>
            )}
        </>
    );
}

Login.layout = {
    title: 'Iniciar sessão',
    description: 'Introduza as suas credenciais para aceder ao painel',
};
