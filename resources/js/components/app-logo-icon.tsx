import { type SharedData } from '@/types';
import { usePage } from '@inertiajs/react';

export default function AppLogoIcon() {
    const { site } = usePage<SharedData>().props;
    return <img src={'/storage' + site.logo} alt={site.name} />;
}
