import { get } from '.';
import type { UserModel } from '$lib/models/user';
import type { Result } from '$lib/models';

export const me = async (
	ctx: AbortSignal,
	f: typeof fetch | undefined = undefined
): Promise<Result<UserModel>> => {
	return get<Result<UserModel>>(ctx, '/me', undefined, f);
};