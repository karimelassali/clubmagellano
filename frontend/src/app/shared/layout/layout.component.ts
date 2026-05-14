import { Component } from '@angular/core';
import { RouterLink } from '@angular/router';

@Component({
  selector: 'app-layout',
  standalone: true,
  imports: [RouterLink],
  template: `
    <div class="container">
      <div class="card">
        <h1>Senior API Challenge</h1>
        <nav>
          <a routerLink="/">Dashboard</a>
          <a routerLink="/requests">Requests</a>
          <a routerLink="/requests/new">New request</a>
          <a routerLink="/login">Login</a>
        </nav>
      </div>
      <ng-content></ng-content>
    </div>
  `
})
export class LayoutComponent {}
