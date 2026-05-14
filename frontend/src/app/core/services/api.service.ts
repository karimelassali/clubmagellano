import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';

@Injectable({ providedIn: 'root' })
export class ApiService {
  baseUrl = 'http://localhost:8000/api';

  constructor(private http: HttpClient) {}

  getProjects() {
    return this.http.get<any>(`${this.baseUrl}/projects`);
  }

  getRequests(filters: Record<string, string> = {}) {
    let params = new HttpParams();
    Object.entries(filters).forEach(([key, value]) => {
      if (value) params = params.set(key, value);
    });

    return this.http.get<any>(`${this.baseUrl}/processing-requests`, { params });
  }

  getRequest(id: string) {
    return this.http.get<any>(`${this.baseUrl}/processing-requests/${id}`);
  }

  createRequest(payload: any) {
    return this.http.post<any>(`${this.baseUrl}/processing-requests`, payload);
  }

  retryRequest(id: string) {
    return this.http.post<any>(`${this.baseUrl}/processing-requests/${id}/retry`, {});
  }

  getStats() {
    return this.http.get<any>(`${this.baseUrl}/dashboard/stats`);
  }
}
