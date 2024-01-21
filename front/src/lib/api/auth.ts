import { object, string, boolean } from 'zod';
import type { z } from 'zod';
import { get, post } from '.';

const Login = object({
	email: string().email().max(255),
	password: string().min(8),
});

export type LoginRequest = z.infer<typeof Login>;

export type LoginResponse = {
    token: string
};

export const auth = async (
	ctx: AbortSignal,
	req: LoginRequest,
	f: typeof fetch | undefined = undefined
): Promise<LoginResponse> => {
	return await post<LoginResponse>(ctx, '/login', {
		body: req,
		f
	});
};

export const getPermissions = async (
	ctx: AbortSignal,
	f: typeof fetch | undefined = undefined
): Promise<[]> => {
	return get<[]>(ctx, '/auth/permissions', undefined, f);
};


export const logout = async (ctx: AbortSignal, f: typeof fetch | undefined = undefined) => {
	await post(ctx, '/logout', {
		f: f
	});
};