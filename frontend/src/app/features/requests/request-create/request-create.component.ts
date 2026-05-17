import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule, JsonPipe } from '@angular/common';
import { ApiService } from '../../../core/services/api.service';

@Component({
  selector: 'app-request-create',
  standalone: true,
  imports: [FormsModule, CommonModule, JsonPipe],
  templateUrl: './request-create.component.html',
  styleUrl: './request-create.component.css'
})
export class RequestCreateComponent implements OnInit {
  projects: any[] = [];
  projectId: number | null = null;
  reference = '';
  payload = JSON.stringify({
    customer: 'ACME',
    items: [
      { sku: 'A1', qty: 2, price: 10 },
      { sku: 'B2', qty: 1, price: 30 }
    ]
  }, null, 2);
  message = '';
  response: unknown = null;

  constructor(private api: ApiService) {}

  ngOnInit(): void {
    this.api.getProjects().subscribe({
      next: (response) => this.projects = response.data,
      error: () => this.projects = []
    });
  }

  submit() {
    try {
      const payload_json = JSON.parse(this.payload);

      this.api.createRequest({
        project_id: this.projectId,
        reference: this.reference,
        payload_json
      }).subscribe({
        next: (response) => {
          this.message = 'Request created';
          this.response = response.data;
        },
        error: (error) => {
          this.message = 'Creation failed';
          this.response = error?.error ?? null;
        }
      });
    } catch {
      this.message = 'Payload JSON non valido';
    }
  }
}
