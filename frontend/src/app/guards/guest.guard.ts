import { CanActivateFn, Router } from '@angular/router';
import { inject } from '@angular/core';
import { AuthService } from '../core/services/auth.service';

export const guestGuard: CanActivateFn = (route, state) => {
  const authService = inject(AuthService);
  const router = inject(Router);

  console.log('[GuestGuard] Checking auth state:', authService.isAuthenticated());

  if (authService.isAuthenticated()) {
    console.log('[GuestGuard] User is authenticated, redirecting to dashboard (/)');
    return router.navigate(['/']);
  }

  console.log('[GuestGuard] User is not authenticated, allowing access to login');
  return true;
};
