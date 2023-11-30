
export interface CompanyModel {
    id: string;
    name: string;
    created_at: string;
    updated_at?: string;
}

export interface CompanyRequest {
    name: string;
}