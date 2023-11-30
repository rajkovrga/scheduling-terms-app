import { writable } from 'svelte/store';
import type { Writable } from "svelte/store";
import type { UserModel } from '$lib/models';

export type User = {
    type: 'authenticated' | 'unauthenticated';
    user: UserModel | null;
    timeZone?: string;
    locale?: string;
};

export const user = <Writable<User>>writable({
    type: 'unauthenticated',
    user: null,
    locale: undefined,
    timeZone: undefined
});