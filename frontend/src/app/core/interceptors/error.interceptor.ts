import { HttpInterceptorFn } from '@angular/common/http';
import { Router } from '@angular/router';
import { catchError, throwError } from 'rxjs';
import { inject } from '@angular/core';
import { ToastrService } from 'ngx-toastr';

export const errorInterceptor: HttpInterceptorFn = (req, next) => {
  const router = inject(Router);
  const toast = inject(ToastrService);
  // toast.error('Test');
  return next(req).pipe(
    catchError(err => {
      console.log("Error: ", err);
      //If the user current token not valid or expired ot noth authenticated at all redirect to login page.
      if (err.status == 401) {
        toast.error(err.error?.message || 'An unexpected error occurred');
        localStorage.removeItem("auth_token");
        router.navigate(['/login']);
      }
      else if (err.status == 500) {
        toast.error(err.error?.message || 'An unexpected error occurred');
      }
      else if (err.status == 404) {
        toast.error(err.error?.message || 'An unexpected error occurred');
      }
      else if (err.status == 403) {
        toast.error(err.error?.message || 'An unexpected error occurred');
      }
      else if (err.status == 400) {
        toast.error(err.error?.message || 'An unexpected error occurred');
      }
      else if (err.status == 502) {
        toast.error("Errore interno del server");
      }
      else {
        toast.error(err.error?.message || 'An unexpected error occurred');
      }
      return throwError(() => err);
    })
  );
};
