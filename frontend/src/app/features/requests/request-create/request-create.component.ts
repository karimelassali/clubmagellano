import { Component, OnInit } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { CommonModule, JsonPipe } from '@angular/common';
import { RouterLink } from '@angular/router';
import { ApiService } from '../../../core/services/api.service';
import { SpinnerComponent } from '../../../shared/spinner/spinner.component';
import { Project } from '../../../core/models/project.model';
import { ProcessingRequest } from '../../../core/models/request.model';

@Component({
  selector: 'app-request-create',
  standalone: true,
  imports: [FormsModule, CommonModule, JsonPipe, RouterLink, SpinnerComponent],
  templateUrl: './request-create.component.html',
  styleUrl: './request-create.component.css'
})
export class RequestCreateComponent implements OnInit {
  projects: Project[] = [];
  projectId: number | null = null;
  reference = '';
  payload = JSON.stringify({
    customer: 'ACME',
    items: [
      { sku: 'A1', qty: 2, price: 10 },
      { sku: 'B2', qty: 1, price: 30 }
    ]
  }, null, 2);
  
  // Loading states
  loadingProjects = false;
  submitting = false;
  
  message = '';
  response: ProcessingRequest | null = null;

  constructor(private api: ApiService) {}

  ngOnInit(): void {
    this.loadingProjects = true;
    this.api.getProjects().subscribe({
      next: (response) => {
        this.projects = response.data;
        this.loadingProjects = false;
      },
      error: () => {
        this.projects = [];
        this.loadingProjects = false;
      }
    });
  }

  submit() {
    if (!this.projectId) {
      this.message = 'Seleziona un progetto';
      this.response = null;
      return;
    }
    if (!this.reference.trim()) {
      this.message = 'Inserisci un riferimento';
      this.response = null;
      return;
    }

    try {
      const payload_json = JSON.parse(this.payload);
      this.submitting = true;
      this.message = '';
      this.response = null;

      this.api.createRequest({
        project_id: this.projectId,
        reference: this.reference.trim(),
        payload_json
      }).subscribe({
        next: (response) => {
          this.message = 'Request created successfully!';
          this.response = response.data;
          this.submitting = false;
          // Optionally clear fields on success
          this.reference = '';
        },
        error: (error) => {
          this.message = error?.error?.message || 'Creation failed';
          this.response = error?.error ?? null;
          this.submitting = false;
        }
      });
    } catch (e) {
      this.message = 'Payload JSON non valido';
      this.response = null;
    }
  }
}
