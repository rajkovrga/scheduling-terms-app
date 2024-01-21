export interface Result<T> {
	data: T;
}

export interface TimestampModel {
    created_at: string;
    updated_at: string;
}