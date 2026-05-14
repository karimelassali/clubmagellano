import { Project } from './project.model';

export interface ProcessingRequestItem {
  sku: string;
  qty: number;
  price: number;
}

export interface ProcessingRequestPayload {
  customer: string;
  items: ProcessingRequestItem[];
}

export interface ProcessingRequest {
  id: number;
  project_id: number;
  reference: string;
  status: string;
  payload_json: ProcessingRequestPayload;
  result_json?: unknown;
  error_message?: string | null;
  processed_at?: string | null;
  created_at?: string | null;
  project?: Project;
}
