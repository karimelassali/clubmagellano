import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({ providedIn: 'root' })
export class AuthService {
  tokenKey = 'auth_token';

  constructor(private http: HttpClient) {}

  login(email: string, password: string) {
    return this.http.post<any>('http://localhost:8000/api/login', { email, password });
  }

  saveToken(token: string) {
    localStorage.setItem(this.tokenKey, token);
  }

  getToken(): string | null {
    return localStorage.getItem(this.tokenKey);
  }

  me() {
    return this.http.get('http://localhost:8000/api/me');
  }
}
