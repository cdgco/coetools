import { DefaultLayout } from '@/layouts';
import { LoadingOverlay } from '@/components';

function LoadingPage() {
    return (
        <DefaultLayout>
            <LoadingOverlay />
        </DefaultLayout>
    )
}

export default LoadingPage;
