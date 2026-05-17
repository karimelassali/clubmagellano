import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { ApiService } from '../../../core/services/api.service';

@Component({
  selector: 'app-request-list',
  standalone: true,
  imports: [CommonModule, RouterLink, FormsModule],
  templateUrl: './request-list.component.html',
  styleUrl: './request-list.component.css'
})
export class RequestListComponent implements OnInit {
  rows: any[] = [];
  filters: Record<string, string> = { reference: '', project_id: '', status: '' };

  constructor(private api: ApiService) {}

  ngOnInit(): void {
    this.load();
  }

  load() {
    this.api.getRequests(this.filters).subscribe({
      next: (response) => this.rows = response.data?.data ?? response.data ?? [],
      error: () => this.rows = []
    });
  }
}
