export interface ApiResponse<T> {
  data: T;
}

export interface ApiPaginatedResponse<T> {
  data: {
    data: T[];
    current_page: number;
    last_page: number;
    total: number;
    per_page: number;
  };
}
