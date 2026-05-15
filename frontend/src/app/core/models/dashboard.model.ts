

export interface ByStatus {
  pending: number;
  processing: number;
  completed: number;
  failed: number;
}

export interface DashboardModel {
  total: number;
  requests: Request[];
  by_status: ByStatus;
  created_today: number;
  avg_processing_seconds: number;
  error?: string;
} 
