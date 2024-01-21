export type ValidationError = {
	type: 'validation-error';
	message: string;
	errors: {
		[x: string]: Array<string>;
	};
};

export type ServerError = {
	type: 'server-error';
	message: string;
};

export type UnAuthorizedError = {
	type: 'unauthorized-error';
	message: string;
};

export type ForbidenError = {
	type: 'forbiden-error';
	message: string;
};

export type ApiError = ValidationError | ServerError | UnAuthorizedError | ForbidenError;

export const getValidationError = (validationError: ApiError | null, field: string) => {
	return (validationError as ValidationError)?.errors[field][0] as string;
};

export const hasValidationError = (validationError: ApiError | null, field: string): boolean => {
	if (!validationError) {
		return false;
	}

	if (validationError.type !== 'validation-error') {
		return false;
	}

	return field in validationError.errors && validationError.errors[field].length > 0;
};