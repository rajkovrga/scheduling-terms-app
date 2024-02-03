import { redirect, type Handle, type HandleFetch } from '@sveltejs/kit';

import { me } from '$lib/api/user';
import { env } from '$env/dynamic/public';

const allowedUnauthenticatedRoutes = ['/(auth)/login'];

import { csrf } from '$lib/api';

export const handleFetch = (async ({ request, fetch, event }) => {
	if (request.url.startsWith(env.PUBLIC_BASE_URL)) {
		request.headers.set('cookie', event.request.headers.get('cookie') ?? '');
		if (request.method !== 'GET' && request.method !== 'OPTIONS') {
			const controller = new AbortController();
			request.headers.append('X-XSRF-TOKEN', await csrf(controller.signal, fetch));
			controller.abort();
		}

		request.headers.append('Accept', 'application/json');
		request.headers.append('X-Requested-With', 'XMLHttpRequest');
	}

	return fetch(request);
}) satisfies HandleFetch;

export const handle = (async ({ event, resolve }) => {
	if (allowedUnauthenticatedRoutes.indexOf(event.route.id ?? '') !== -1) {
		return await resolve(event);
	}

	const controller = new AbortController();

	try {
		event.locals.session = {
			type: 'authenticated',
			user: (await me(controller.signal, event.fetch)).data
		};

		return await resolve(event);
	} catch (err) {
		throw redirect(307, '/login');
	} finally {
		controller.abort();
	}
}) satisfies Handle;