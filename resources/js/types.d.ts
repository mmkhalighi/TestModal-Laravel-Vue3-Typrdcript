import '@inertiajs/core';

declare module '@inertiajs/core' {
    interface PageProps {
        auth: {
            user: {
                id: number;
                first_name: string;
                last_name: string;
                username: string;
                role: 'admin' | 'user';
            } | null;
        };

        flash: {
            success: string | null;
            error: string | null;
        };
    }
}
