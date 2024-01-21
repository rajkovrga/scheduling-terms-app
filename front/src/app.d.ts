// See https://kit.svelte.dev/docs/types#app
// for information about these interfaces

import type { User } from '$lib/store';

declare global {
	namespace App {
		// interface Error {}
		interface Locals {
			session: User;
		}
		// interface PageData {}
		// interface Platform {}
	}
}

export {};