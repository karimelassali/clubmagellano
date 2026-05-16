
export interface DashboardRequest {
  id: number;
  reference: string;
  created_by: string;
  status: string;
  created_at: string;
}

export interface ByStatus {
  pending: number;
  processing: number;
  completed: number;
  failed: number;
}

export interface DashboardStats {
  total: number;
  requests: DashboardRequest[];
  by_status: ByStatus;
  created_today: number;
  avg_processing_seconds: number;
  error?: string;
}

export interface DashboardModel {
  data: DashboardStats;
}
