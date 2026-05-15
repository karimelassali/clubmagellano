import { Component, inject } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { AuthService } from '../../core/services/auth.service';

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

          @if(auth.isAuthenticated()) {
            <button (click)="logout()">Logout</button>
          }

        </nav>
      </div>
      <ng-content></ng-content>
    </div>
  `
})
export class LayoutComponent {
  public auth = inject(AuthService);
  private router = inject(Router);

  logout() {
    this.auth.logout();
    this.router.navigate(['/login']);
  }

}
