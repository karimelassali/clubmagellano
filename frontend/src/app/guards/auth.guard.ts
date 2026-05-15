import { CanActivateFn, Router } from '@angular/router';

export const authGuard: CanActivateFn = (route, state) => {
  const router = new Router();
  if (localStorage.getItem('auth_token') === null) {
    return router.navigate(['/login']);
  }
  else {
    return true;
  }
};
