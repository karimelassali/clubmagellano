import { Component, inject } from '@angular/core';
import { Router, RouterLink } from '@angular/router';
import { AuthService } from '../../core/services/auth.service';
import { LoginComponent } from '../../features/auth/login.component';

@Component({
  selector: 'app-layout',
  standalone: true,
  imports: [RouterLink, LoginComponent],

  //Only show the navbar when there is a logged in user.
  template: `

  @if(auth.isAuthenticated()) {
    <div class="container">
      <div class="card">
        <h1>Senior API Challenge</h1>
        <nav>
          <a routerLink="/">Dashboard</a>
          <a routerLink="/requests">Requests</a>
          <a routerLink="/requests/new">New request</a>
          <a routerLink="/login">Login</a>
          <button (click)="logout()">Logout</button>
        </nav>
      </div>
      <ng-content></ng-content>
    </div>
  }
  @else {
    <app-login></app-login>
  }
    
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
