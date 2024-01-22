import type { ApiError } from '$lib/models/errors';
import { PUBLIC_BASE_URL } from '$env/dynamic/public';

export type Method = 'GET' | 'POST' | 'PUT' | 'DELETE' | 'PATCH' | 'OPTIONS';
export type ContentType = 'json' | 'form' | 'formData';

type Header = {
	[key: string]: string;
};

type Request = {
	// eslint-disable-next-line @typescript-eslint/no-explicit-any
	body?: any | undefined;
	contentType?: ContentType;
	inputHeaders?: Header;
	csrfToken?: string | undefined;
	f?: typeof fetch | undefined;
};

export const requester = async <TResponse>(
	ctx: AbortSignal,
	method: Method,
	url: string,
	request: Request = {}
): Promise<TResponse> => {
	const headers: Headers = new Headers();
	headers.append('Accept', 'application/json');
	headers.append('X-Requested-With', 'XMLHttpRequest');

	if (method !== 'GET' && method !== 'OPTIONS') {
		if (request.csrfToken !== undefined) {
			headers.append('X-XSRF-TOKEN', request.csrfToken);
		} else {
			headers.append('X-XSRF-TOKEN', await csrf(ctx, request.f));
		}
	}

	if (request.inputHeaders !== undefined) {
		Object.keys(request.inputHeaders).forEach((key) => {
			// eslint-disable-next-line @typescript-eslint/no-non-null-assertion
			headers.append(key, request.inputHeaders![key]);
		});
	}

	let bodyString: string | FormData | null = null;

	if (request.body !== undefined) {
		if (request.contentType === undefined) {
			request.contentType = 'json';
		}

		switch (request.contentType) {
			case 'json': {
				bodyString = JSON.stringify(request.body);
				headers.set('Content-Type', 'application/json');
				break;
			}
			case 'form': {
				const data = new URLSearchParams();

				Object.keys(request.body).forEach((key) => {
					if (typeof request.body[key] !== 'string') {
						throw new Error('For form data, all values must be strings');
					}

					data.append(key, request.body[key] as string);
				});

				headers.set('Content-Type', 'application/x-www-form-urlencoded');
				break;
			}
			case 'formData': {
				const data = new FormData();
				Object.keys(request.body).forEach((key) => {
					data.append(key, request.body[key]);
				});

				headers.set('Content-Type', 'multipart/form-data');
				bodyString = data;
				break;
			}
		}
	}
	const res = await (request.f ?? fetch)(`${PUBLIC_BASE_URL}${url}`, {
		method,
		mode: 'cors',
		cache: 'no-cache',
		referrerPolicy: 'origin-when-cross-origin',
		redirect: 'error',
		credentials: 'include',
		headers,
		keepalive: true,
		signal: ctx,
		body: bodyString
	});

	if (res.status > 199 && res.status < 300) {
		if (res.status !== 204) {
			return res.json() as Promise<TResponse>;
		}

		return {} as TResponse;
	}

	throw {
		response: res,
		// body: await res.text(),
		message: 'Request failed'
	};
};

const extractCsrf = (): string | undefined => {
	const cookie = document.cookie
		.split(';')
		.find((cookie) =>
			cookie
				.trim()
				.substring(0, 'XSRF-TOKEN'.length)
				.toLocaleUpperCase()
				.startsWith('XSRF-TOKEN', 0)
		);

	if (cookie === undefined) {
		return undefined;
	}

	const value = decodeURIComponent(cookie.trimStart().substring('XSRF-TOKEN='.length));

	return value;
};

export const csrf = async (
	ctx: AbortSignal,
	f: typeof fetch | undefined = undefined
): Promise<string> => {
	const csrf = extractCsrf();

	if (csrf) {
		return csrf;
	}

	if (f === undefined) {
		f = fetch;
	}

	const headers = new Headers();
	headers.append('Accept', 'application/json');
	headers.append('X-Requested-With', 'XMLHttpRequest');

	const res = await f(`localhost/csrf`, {
		method: 'GET',
		mode: 'cors',
		cache: 'no-cache',
		referrerPolicy: 'origin-when-cross-origin',
		redirect: 'error',
		credentials: 'include',
		headers,
		signal: ctx
	});

	if (res.status !== 204) {
		throw new Error('Could not get csrf token, status: ' + res.status);
	}

	// eslint-disable-next-line @typescript-eslint/no-non-null-assertion
	return extractCsrf()!;
};
export const get = <T>(
	ctx: AbortSignal,
	url: string,
	headers: Header | undefined = undefined,
	f: typeof fetch | undefined = undefined
) => requester<T>(ctx, 'GET', url, { inputHeaders: headers, csrfToken: undefined, f });
export const post = <T>(ctx: AbortSignal, url: string, request: Request = {}) =>
	requester<T>(ctx, 'POST', url, request);

export const put = <T>(ctx: AbortSignal, url: string, request: Request = {}) =>
	requester<T>(ctx, 'PUT', url, request);
export const patch = <T>(ctx: AbortSignal, url: string, request: Request = {}) =>
	requester<T>(ctx, 'PATCH', url, request);

export const deleteReq = <T>(
	ctx: AbortSignal,
	url: string,
	headers: Header | undefined = undefined,
	csrfToken: string | undefined = undefined,
	f: typeof fetch | undefined = undefined
) => requester<T>(ctx, 'DELETE', url, { inputHeaders: headers, csrfToken, f });

export const getError = async (response: Response): Promise<ApiError> => {
	const status = response.status;

	if (status === 401) {
		return {
			type: 'unauthorized-error',
			message: 'You are not authenticated, goto the login page'
		};
	}

	if (status === 403) {
		return {
			type: 'forbiden-error',
			message: 'You are forbidden to execute this actions'
		};
	}

	if (status === 422) {
		return {
			type: 'validation-error',
			...(await response.json())
		};
	}

	return {
		type: 'server-error',
		message: 'An error has occurred, please try again later'
	};
};