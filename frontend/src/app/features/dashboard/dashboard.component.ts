import { Component, OnInit } from '@angular/core';
import { JsonPipe } from '@angular/common';
import { ApiService } from '../../core/services/api.service';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [JsonPipe],
  template: `
    <div class="card">
      <h2>Dashboard</h2>
      <button (click)="load()">Refresh</button>
      <pre>{{ stats | json }}</pre>
    </div>
  `
})
export class DashboardComponent implements OnInit {
  stats: unknown = null;

  constructor(private api: ApiService) {}

  ngOnInit(): void {
    this.load();
  }

  load() {
    this.api.getStats().subscribe({
      next: (response) => this.stats = response.data,
      error: () => this.stats = { error: 'Unable to load stats' }
    });
  }
}
