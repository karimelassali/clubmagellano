import { Routes } from '@angular/router';
import { LoginComponent } from './features/auth/login.component';
import { RequestListComponent } from './features/requests/request-list.component';
import { RequestCreateComponent } from './features/requests/request-create.component';
import { RequestDetailComponent } from './features/requests/request-detail.component';
import { DashboardComponent } from './features/dashboard/dashboard.component';

export const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: '', component: DashboardComponent },
  { path: 'requests', component: RequestListComponent },
  { path: 'requests/new', component: RequestCreateComponent },
  { path: 'requests/:id', component: RequestDetailComponent },
];
