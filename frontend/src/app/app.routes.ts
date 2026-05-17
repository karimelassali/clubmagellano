import { Routes } from '@angular/router';
import { LoginComponent } from './features/auth/login.component';
import { RequestListComponent } from './features/requests/request-list.component';
import { RequestCreateComponent } from './features/requests/request-create.component';
import { RequestDetailComponent } from './features/requests/request-detail.component';
import { DashboardComponent } from './features/dashboard/dashboard.component';
import { authGuard } from './guards/auth.guard';
import { guestGuard } from './guards/guest.guard';

export const routes: Routes = [
  { path: 'login', component: LoginComponent, canActivate: [guestGuard] },
  { path: 'dashboard', redirectTo: '', pathMatch: 'full' },
  { path: '', component: DashboardComponent, canActivate: [authGuard] },
  { path: 'requests', component: RequestListComponent, canActivate: [authGuard] },
  { path: 'requests/new', component: RequestCreateComponent, canActivate: [authGuard] },
  { path: 'requests/:id', component: RequestDetailComponent, canActivate: [authGuard] },
];
