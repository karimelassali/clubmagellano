import { Injectable ,signal} from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { AuthResponse, User } from '../models/auth.model';
import { switchMap, tap, catchError,of } from 'rxjs';

@Injectable({ providedIn: 'root' })
export class AuthService {

currentUser = signal<User | null>(null);


  constructor(private http: HttpClient) {}

  fetchCurrentUser() {
  return this.me().pipe(
    tap(user => this.currentUser.set(user)),
    catchError(() => {
      this.currentUser.set(null);
      return of(null);
    })
  );
}

login(email: string, password: string) {
  return this.http.get('/sanctum/csrf-cookie').pipe(
    switchMap(() => this.http.post<AuthResponse>('/api/login', { email, password })),
    switchMap(() => this.fetchCurrentUser()) // fetches the user and stores it in the signal
  );
}
  // saveToken(token: string) {
  //   localStorage.setItem(this.tokenKey, token);
  // }



  me() {
    //Usingg User Interface for type safety.
    return this.http.get<User>('/api/me');
  }

  isAuthenticated(): boolean {
  return this.currentUser() !== null; // reads from signal, sync
}

logout() {
  return this.http.post('/api/logout', {}).pipe(
    tap(() => this.currentUser.set(null)) // clear user on logout
  );
}

}
