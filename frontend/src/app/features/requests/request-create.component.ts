import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule, JsonPipe } from '@angular/common';
import { ApiService } from '../../core/services/api.service';

@Component({
  selector: 'app-request-create',
  standalone: true,
  imports: [FormsModule, CommonModule, JsonPipe],
  template: `
    <div class="card">
      <h2>Create request</h2>

      <div>
        <label>Project</label>
        <select [(ngModel)]="projectId">
          <option [ngValue]="null">Select</option>
          <option *ngFor="let p of projects" [ngValue]="p.id">{{ p.name }}</option>
        </select>
      </div>

      <div>
        <label>Reference</label>
        <input [(ngModel)]="reference" />
      </div>

      <div>
        <label>Payload JSON</label>
        <textarea [(ngModel)]="payload" rows="12" cols="80"></textarea>
      </div>

      <button (click)="submit()">Create</button>
      <p>{{ message }}</p>
      <pre>{{ response | json }}</pre>
    </div>
  `
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
