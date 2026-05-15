import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { AuthResponse, User } from '../models/auth.model';

@Injectable({ providedIn: 'root' })
export class AuthService {
  tokenKey = 'auth_token';

  constructor(private http: HttpClient) {}

  login(email: string, password: string) {
    // console.log(email, password);


    //Using AuthResponse for type safety.
    return this.http.post<AuthResponse>('http://localhost:8000/api/login', { email, password });
  }

  saveToken(token: string) {
    localStorage.setItem(this.tokenKey, token);
  }

  getToken(): string | null {
    return localStorage.getItem(this.tokenKey);
  }

  me() {
    //Usingg User Interface for type safety.
    return this.http.get<User>('http://localhost:8000/api/me');
  }

  logout() {
    localStorage.removeItem(this.tokenKey);
  }

  isAuthenticated(): boolean {
    return !!this.getToken();
  }
}
