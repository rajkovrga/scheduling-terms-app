export interface JobModel {
    name: string;
}

export interface JobRequest {
    name: string;
    during: number;
    company_id: number;
}