import type {JobModel} from "./job";
import type {UserModel} from "./user";
import type {CompanyModel} from "./company";

export interface TermModel {
    start_date: string;
    end_date: string;
    company: CompanyModel | null;
    user_id: UserModel | null;
    job: JobModel | null;
}

export interface CreateUpdateTermModel {
    start_date: string;
    company_id: number;
    user_id: number;
    job_id: number;
}