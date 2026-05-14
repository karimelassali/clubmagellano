import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../core/services/auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [FormsModule],
  template: `
    <div class="card">
      <h2>Login</h2>
      <p>Utente seed: senior@example.com / password</p>
      <form (ngSubmit)="submit()">
        <div><input [(ngModel)]="email" name="email" placeholder="Email"></div>
        <div><input [(ngModel)]="password" name="password" type="password" placeholder="Password"></div>
        <button type="submit">Login</button>
      </form>
      <p>{{ message }}</p>
    </div>
  `
})
export class LoginComponent {
  email = 'senior@example.com';
  password = 'password';
  message = '';

  constructor(private auth: AuthService, private router: Router) {}

  submit() {
    this.auth.login(this.email, this.password).subscribe({
      next: (response) => {
        this.auth.saveToken(response.token);
        this.message = 'Login eseguito';
        this.router.navigateByUrl('/');
      },
      error: () => {
        this.message = 'Errore login';
      }
    });
  }
}
