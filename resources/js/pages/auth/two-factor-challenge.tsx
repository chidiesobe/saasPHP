import { useState } from 'react';
// Components
import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';

export default function ConfirmCode() {
    const [usingRecoveryCode, setUsingRecoveryCode] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({
        code: '',
        recovery_code: '',
    });

    const twoFactorChallenge: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('two-factor.login.store'), {
            onFinish: () => reset('code', 'recovery_code'),
        });
    };

    return (
        <AuthLayout title="Two Factor Authentication" description="2FA Enabled. Please confirm your 2FA or recovery code before continuing.">
            <Head title="Confirm code" />

            <form onSubmit={twoFactorChallenge}>
                <div className="space-y-6">
                    <div className="grid gap-2">
                        <Label htmlFor="code">Code</Label>
                        {usingRecoveryCode ? (
                            <Input
                                id="recovery_code"
                                type="text"
                                name="recovery_code"
                                placeholder="Enter a recovery code"
                                value={data.recovery_code}
                                onChange={(e) => setData('recovery_code', e.target.value)}
                            />
                        ) : (
                            <Input
                                id="code"
                                type="text"
                                name="code"
                                placeholder="Enter 6-digit authenticator code"
                                value={data.code}
                                onChange={(e) => setData('code', e.target.value)}
                            />
                        )}

                        <InputError message={errors.code} />
                    </div>

                    <div className="flex items-center">
                        <Button className="w-full" disabled={processing}>
                            {processing && <LoaderCircle className="h-4 w-4 animate-spin" />}
                            Confirm code
                        </Button>
                    </div>
                    <div className="flex items-center justify-between">
                        <label className="flex items-center space-x-2">
                            <input type="checkbox" checked={usingRecoveryCode} onChange={(e) => setUsingRecoveryCode(e.target.checked)} />
                            <span>Use recovery code instead</span>
                        </label>
                    </div>
                </div>
            </form>
        </AuthLayout>
    );
}
