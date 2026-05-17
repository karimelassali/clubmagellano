import { Component, OnInit, signal } from '@angular/core';
import { ActivatedRoute, RouterLink } from '@angular/router';
import { JsonPipe, CommonModule } from '@angular/common';
import { ApiService } from '../../../core/services/api.service';

@Component({
  selector: 'app-request-detail',
  standalone: true,
  imports: [JsonPipe, CommonModule, RouterLink],
  templateUrl: './request-detail.component.html',
  styleUrl: './request-detail.component.css'
})
export class RequestDetailComponent implements OnInit {
  request: any = null;
  message = '';
  isLoading = signal(false);

  requestedId = '';

  constructor(private route: ActivatedRoute, private api: ApiService) {}

  ngOnInit(): void {
    this.load();
  }

  load() {
    const id = this.route.snapshot.paramMap.get('id') ?? '';
    this.requestedId = id;

    if (!id || isNaN(Number(id)) || Number(id) <= 0) {
    this.message = 'Invalid Request ID format provided.';
    this.request = null;
    this.isLoading.set(false);
    return; // We make sure if the passed id its invalid,not a number etc, we stop the api req.
  }
    this.isLoading.set(true);
    this.message = '';
    this.api.getRequest(id).subscribe({
      next: (response) => {
        this.request = response.data;
        this.isLoading.set(false);
      },
      error: () => {
        this.message = 'Unable to load request';
        this.isLoading.set(false);
      }
    });
  }

  retry() {
    const id = this.route.snapshot.paramMap.get('id') ?? '';
    this.isLoading.set(true);
    this.message = '';
    this.api.retryRequest(id).subscribe({
      next: () => {
        this.message = 'Retry successfully dispatched';
        this.load();
      },
      error: () => {
        this.message = 'Retry failed';
        this.isLoading.set(false);
      }
    });
  }
}
