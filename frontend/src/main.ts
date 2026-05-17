import { importProvidersFrom, APP_INITIALIZER } from "@angular/core";
import { provideAnimations } from "@angular/platform-browser/animations";
import { TuiRootModule } from "@taiga-ui/core";
import { bootstrapApplication } from '@angular/platform-browser';
import { provideRouter } from '@angular/router';
import { provideHttpClient, withInterceptors, withXsrfConfiguration } from '@angular/common/http';
import { AppComponent } from './app/app.component';
import { routes } from './app/app.routes';
import { authInterceptor } from './app/core/interceptors/auth.interceptor';
import { errorInterceptor } from './app/core/interceptors/error.interceptor';
import { provideToastr } from 'ngx-toastr';
import { AuthService } from './app/core/services/auth.service';

bootstrapApplication(AppComponent, {
  providers: [
    provideAnimations(),
     provideToastr({
      positionClass: 'toast-top-right',
      timeOut: 3000
    }),
    provideRouter(routes),
    provideHttpClient(withInterceptors([authInterceptor, errorInterceptor]),
  //this allows us to send cookies in the request
    withXsrfConfiguration({
    cookieName: 'XSRF-TOKEN',
    headerName: 'X-XSRF-TOKEN',
  })
  ),
    {
      provide: APP_INITIALIZER,
      useFactory: (authService: AuthService) => () => authService.fetchCurrentUser(),
      deps: [AuthService],
      multi: true
    },
    importProvidersFrom(TuiRootModule)
  ]
}).catch(err => console.error(err));
