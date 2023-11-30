import type {CompanyModel} from "./company";
import type {RoleModel} from "./auth";

export interface UserModel {
    id: number;
    email: string;
    company: CompanyModel | null;
    role: RoleModel | null;
}

export interface UserRequest {
    email: string;
    password: string;
    confirm_password:string;
    company_id: number;
    role_id: number;
}