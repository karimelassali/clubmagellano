import { CanActivateFn, Router } from '@angular/router';
import { inject } from '@angular/core';
import { AuthService } from '../core/services/auth.service';

export const authGuard: CanActivateFn = (route, state) => {

  //Removing old storageauth checking , replace with the new session auth method
  
  const authService = inject(AuthService);
  const router = inject(Router);
  
  if (!authService.isAuthenticated()) {
    return router.navigate(['/login']);
  }
    return true;
  
};
