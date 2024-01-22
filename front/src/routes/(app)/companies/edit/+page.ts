import type { PageLoad } from './$types';

export const load = (async ({ parent }) => {

    return {
        title: 'Edit Company'
    };
}) satisfies PageLoad;