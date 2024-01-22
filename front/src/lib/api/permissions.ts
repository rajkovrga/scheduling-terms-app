import type { User } from '$lib/store';

export type TermPermissions =
	| 'view self terms'
	| 'view terms'
	| 'create client'
	| 'edit client'
	| 'delete client';

export type JobPermissions =
	| 'view all jobs'
	| 'view jobs'
	| 'create job'
	| 'edit job'
	| 'delete job';

export type UserPermissions =
	| 'view all users'
	| 'view users'
	| 'create users'
	| 'create employee user'
	| 'edit user'
	| 'edit own user'
	| 'delete own user'
	| 'delete user';

export type CompanyPermissions =
	| 'view all companies'
	| 'create company'
    | 'edit company'
	| 'delete company'
	| 'edit company';

export type ProfilePermissions =
    | 'edit profile';

export type Permission =
	| TermPermissions
	| UserPermissions
	| JobPermissions
	| CompanyPermissions
    | ProfilePermissions;

export const hasPermission = (user: User, permissions: Permission): boolean => {
	return user.permissions!.includes(permissions);
};

export const hasAnyPermission = (user: User, ...permissions: Permission[]): boolean => {
	return permissions.some((permission) => hasPermission(user, permission));
};

export const hasAllPermissions = (user: User, ...permissions: Permission[]): boolean => {
	return permissions.every((permission) => hasPermission(user, permission));
};
