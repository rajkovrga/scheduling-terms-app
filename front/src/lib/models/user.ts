import type {CompanyModel} from "./company";
import type {RoleModel} from "./auth";
import type { TimestampModel } from ".";

export interface UserModel extends TimestampModel {
    id: number;
    email: string;
    company: CompanyModel | null;
}

export interface UserRequest {
    email: string;
    password: string;
    confirm_password:string;
    company_id: number;
    role_id: number;
}