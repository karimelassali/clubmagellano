import { Component, inject, OnInit, signal } from '@angular/core';
import { JsonPipe } from '@angular/common';
import { ApiService } from '../../core/services/api.service';
import { DashboardStats, DashboardModel } from '../../core/models/dashboard.model';
import { PaginationComponent } from '../../shared/pagination/pagination.component';
@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [JsonPipe, PaginationComponent],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.css',
})
export class DashboardComponent implements OnInit {
  isLoading = signal(false);
  stats!: DashboardStats;

  constructor(private api: ApiService) { }

  ngOnInit(): void {
    this.load();
  }

  load() {
    this.isLoading.set(true);
    this.api.getStats().subscribe({
      next: (response: DashboardModel) => this.stats = response.data,
      error: () => this.isLoading.set(false),
      complete: () => this.isLoading.set(false)
    });
  }
}
