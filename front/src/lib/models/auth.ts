import type {CompanyModel} from "./company";

export interface AuthUserModel {
    id: number;
    email: string;
    permissions: string[];
    role: string;
    company: CompanyModel | null;
}

export interface RoleModel {
    id: number;
    name: string;
}