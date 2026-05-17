import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import { FormsModule } from '@angular/forms';
import { ApiService } from '../../../core/services/api.service';
import { PaginationComponent } from '../../../shared/pagination/pagination.component';
import { TuiTableModule } from '@taiga-ui/addon-table';
import { TuiBadgeModule } from '@taiga-ui/kit';

@Component({
  selector: 'app-request-list',
  standalone: true,
  imports: [
    CommonModule, 
    RouterLink, 
    FormsModule, 
    PaginationComponent,
    TuiTableModule,
    TuiBadgeModule
  ],
  templateUrl: './request-list.component.html',
  styleUrl: './request-list.component.css'
})
export class RequestListComponent implements OnInit {
  rows: any[] = [];
  
  filters: Record<string, string> = { reference: '', project_id: '', status: '' };
  
  // Pagination variables
  page: number = 1;
  limit: number = 15; // Laravel API hardcoded size
  lastPage: number = 1;

  constructor(private api: ApiService) {}

  ngOnInit(): void {
    this.load();
  }

  onPageChange(pageIndex: number): void {
    this.page = pageIndex + 1; // Convert 0-index to 1-index for backend API
    this.load();
  }

  applyFilter(): void {
    this.page = 1; // Reset to page 1 on filter
    this.load();
  }

  load() {
    this.api.getRequests(this.filters, this.page, this.limit).subscribe({
      next: (response) => {
        this.rows = response.data?.data ?? response.data ?? [];
        this.page = response.data?.current_page ?? 1;
        this.lastPage = response.data?.last_page ?? 1;
      },
      error: () => {
        this.rows = [];
      }
    });
  }
}
