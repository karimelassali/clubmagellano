import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { JsonPipe, CommonModule } from '@angular/common';
import { ApiService } from '../../../core/services/api.service';

@Component({
  selector: 'app-request-detail',
  standalone: true,
  imports: [JsonPipe, CommonModule],
  templateUrl: './request-detail.component.html',
  styleUrl: './request-detail.component.css'
})
export class RequestDetailComponent implements OnInit {
  request: any = null;
  message = '';

  constructor(private route: ActivatedRoute, private api: ApiService) {}

  ngOnInit(): void {
    this.load();
  }

  load() {
    const id = this.route.snapshot.paramMap.get('id') ?? '';
    this.api.getRequest(id).subscribe({
      next: (response) => this.request = response.data,
      error: () => this.message = 'Unable to load request'
    });
  }

  retry() {
    const id = this.route.snapshot.paramMap.get('id') ?? '';
    this.api.retryRequest(id).subscribe({
      next: () => {
        this.message = 'Retry dispatched';
        this.load();
      },
      error: () => this.message = 'Retry failed'
    });
  }
}
