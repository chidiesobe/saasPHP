import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/auth-layout';
import { Head } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import React, { useState } from 'react';

export default function Maintenance() {
    const [loading, setLoading] = useState(false);

    const handleRefresh: React.MouseEventHandler<HTMLButtonElement> = () => {
        setLoading(true);
        window.location.reload();
    };

    return (
        <AuthLayout title="We'll be back soon!" description="We're currently performing maintenance. Please check back shortly.">
            <Head title="Maintenance Mode" />

            <div className="flex h-full flex-col items-center justify-center space-y-6 py-20 text-center">
                <LoaderCircle className="text-muted-foreground h-12 w-12 animate-spin" />
                <h1 className="text-3xl font-bold">Site Under Maintenance</h1>
                <p className="text-muted-foreground max-w-md text-base">
                    We're making some updates to improve our service. Thanks for your patience!
                </p>
                <Button onClick={handleRefresh} className="mt-4" disabled={loading}>
                    {loading ? <LoaderCircle className="h-4 w-4 animate-spin" /> : 'Refresh'}
                </Button>
            </div>
        </AuthLayout>
    );
}
