import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { DashboardModel } from '../models/dashboard.model';
import { Project } from '../models/project.model';
import { ProcessingRequest } from '../models/request.model';
import { ApiResponse, ApiPaginatedResponse } from '../models/api.model';
import { Observable } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class ApiService {
  baseUrl = '/api';

  constructor(private http: HttpClient) {}

  getProjects(): Observable<ApiResponse<Project[]>> {
    return this.http.get<ApiResponse<Project[]>>(`${this.baseUrl}/projects`);
  }

  getRequests(
    filters: Record<string, string> = {}, 
    page: number = 1, 
    limit: number = 10
  ): Observable<ApiPaginatedResponse<ProcessingRequest>> {
    let params = new HttpParams();
    Object.entries(filters).forEach(([key, value]) => {
      if (value) params = params.set(key, value);
    });

    if (page > 0) params = params.set('page', page.toString());
    if (limit > 0) params = params.set('limit', limit.toString());

    return this.http.get<ApiPaginatedResponse<ProcessingRequest>>(`${this.baseUrl}/processing-requests`, { params });
  }

  getRequest(id: string): Observable<ApiResponse<ProcessingRequest>> {
    return this.http.get<ApiResponse<ProcessingRequest>>(`${this.baseUrl}/processing-requests/${id}`);
  }

  createRequest(payload: { project_id: number | null; reference: string; payload_json: any }): Observable<ApiResponse<ProcessingRequest>> {
    return this.http.post<ApiResponse<ProcessingRequest>>(`${this.baseUrl}/processing-requests`, payload);
  }

  retryRequest(id: string): Observable<ApiResponse<ProcessingRequest>> {
    return this.http.post<ApiResponse<ProcessingRequest>>(`${this.baseUrl}/processing-requests/${id}/retry`, {});
  }

  getStats(): Observable<DashboardModel> {
    return this.http.get<DashboardModel>(`${this.baseUrl}/dashboard/stats`);
  }
}
