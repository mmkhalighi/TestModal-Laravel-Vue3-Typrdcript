import type { PageProps } from '@inertiajs/core';
import type { AuthUser } from './user';

export interface SharedProps {
    [key: string]: unknown;

    auth: {
        user: AuthUser | null;
    };

    flash: {
        success: string | null;
        error: string | null;
    };
}
