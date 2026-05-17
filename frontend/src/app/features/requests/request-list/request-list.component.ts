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
  //Vars to passs for the pagination
  page: number = 1;
  limit: number = 10;
  lastPage: number = 1;

  constructor(private api: ApiService) {}

  ngOnInit(): void {
    this.load();
  }

 nextPage() {
  if (this.page < this.lastPage) {
    this.page++;
    this.load();
  }
}
prevPage() {
  if (this.page > 1) {
    this.page--;
    this.load();
  }
}

  load() {
    
    //BUG INTENZIONALE: La query service non gestisce il caso in cui la pagina è 1.
    
    this.api.getRequests(this.filters, this.page, this.limit).subscribe({
      next: (response) => this.rows = response.data?.data ?? response.data ?? [],
      error: () => this.rows = []
    });
  }
}
