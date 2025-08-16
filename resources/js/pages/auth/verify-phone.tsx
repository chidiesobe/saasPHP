// Components
import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AuthLayout from '@/layouts/auth-layout';

export default function VerifyPhone({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({
        code: '',
    });

    const resendCode: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('phone.send'));
    };

    const verifyCode: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('phone.verify'));
    };

    return (
        <AuthLayout title="Verify phone number" description="Request and enter the verification code sent to your phone.">
            <Head title="Phone number verification" />

            {status === 'verification-link-sent' && (
                <div className="mb-4 text-center text-sm font-medium text-green-600">A new verification code has been sent to your phone number.</div>
            )}

            <form onSubmit={verifyCode} className="space-y-6 text-center">
                <div className="flex items-center gap-2">
                    <Input
                        id="code"
                        name="code"
                        value={data.code}
                        onChange={(e) => setData('code', e.target.value)}
                        placeholder="enter code"
                        className="mx-auto w-64 text-center"
                    />

                    <Button type="submit" disabled={processing}>
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                        Verify code
                    </Button>

                    <Button onClick={resendCode} type="button" disabled={processing} variant="secondary">
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                        Resend code
                    </Button>
                </div>

                {errors.code && <div className="mt-1 text-sm text-red-600">{errors.code}</div>}
                
                <TextLink href={route('logout')} method="post" as="button" className="mx-auto block text-sm">
                    Log out
                </TextLink>
            </form>
        </AuthLayout>
    );
}
