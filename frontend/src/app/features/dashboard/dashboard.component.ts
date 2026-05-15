import { Component, OnInit, signal } from '@angular/core';
import { JsonPipe } from '@angular/common';
import { ApiService } from '../../core/services/api.service';
import { DashboardModel } from '../../core/models/dashboard.model';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [JsonPipe],
  templateUrl: './dashboard.component.html',
  styleUrl: './dashboard.component.css',
})
export class DashboardComponent implements OnInit {
  isLoading = signal(false);
  stats!: DashboardModel;

  constructor(private api: ApiService) {}

  ngOnInit(): void {
    this.load();
  }

  load() {
    this.isLoading.set(true);
    this.api.getStats().subscribe({
      next: (response) => this.stats = response.data,
      error: (error) => this.stats = {  error: 'Unable to load stats', ...error },
      complete: () => this.isLoading.set(false)
    });
  }
}
