import { Component, inject, OnInit, signal } from '@angular/core';
import { CommonModule, JsonPipe } from '@angular/common';
import { ApiService } from '../../core/services/api.service';
import { DashboardStats, DashboardModel } from '../../core/models/dashboard.model';
import { PaginationComponent } from '../../shared/pagination/pagination.component';
import { RouterLink } from '@angular/router';
@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [CommonModule, JsonPipe, PaginationComponent, RouterLink],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.css',
})
export class DashboardComponent implements OnInit {
  isLoading = signal(false);
  stats!: DashboardStats;
  page: number = 1;

  constructor(private api: ApiService) { }

  ngOnInit(): void {
    this.load();
  }

  onPageChange(pageIndex: number): void {
    this.page = pageIndex + 1;
    this.load();
  }

  load() {
    this.isLoading.set(true);
    this.api.getStats(this.page).subscribe({
      next: (response: DashboardModel) => this.stats = response.data,
      error: () => this.isLoading.set(false),
      complete: () => this.isLoading.set(false)
    });
  }
}
