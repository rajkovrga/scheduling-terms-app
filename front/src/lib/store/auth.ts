import type { UserModel } from '$lib/models/user';
import { writable } from 'svelte/store';
import type { Writable } from "svelte/store";

export type User = {
    type: 'authenticated' | 'unauthenticated';
    user: UserModel | null;
    timeZone?: string;
    locale?: string;
    permissions?: string[];
};

export const user = <Writable<User>>writable({
    type: 'unauthenticated',
    user: null,
    locale: undefined,
    timeZone: undefined
});