import { Routes } from '@angular/router';
import { AdminComponent } from './admin.component';
import { AdminUsuariosComponent } from './admin-usuarios.component';
import { AdminReportesComponent } from './admin-reportes.component';
import { AdminPermisosComponent } from './admin-permisos.component';
import { AdminGuard } from './guards/admin.guard';

export const adminRoutes: Routes = [
  {
    path: '',
    component: AdminComponent,
    canActivate: [AdminGuard],
    children: [
      { path: 'usuarios', component: AdminUsuariosComponent },
      { path: 'reportes', component: AdminReportesComponent },
      { path: 'permisos', component: AdminPermisosComponent },
      { path: '', redirectTo: 'usuarios', pathMatch: 'full' }
    ]
  }
];