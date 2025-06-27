import { Routes } from '@angular/router';
import { LoginComponent } from './auth/components/login/login.component';
import { AdminComponent } from './admin.component';
import { RegisterComponent } from './auth/components/register/register.component';
import { ForgotPasswordComponent } from './auth/components/forgot-password/forgot-password.component';
import { BienvenidaComponent } from './bienvenida/bienvenida.component';
import { ResetPasswordComponent } from './auth/components/reset-password/reset-password.component';

import { PerfilCambiarPasswordComponent } from './perfil/perfil-cambiar-password.component';
import { PerfilConfiguracionComponent } from './perfil/perfil-configuracion.component';

export const routes: Routes = [
  { path: '', component: BienvenidaComponent },
  { path: 'login', component: LoginComponent },
  { path: 'register', component: RegisterComponent },
  { path: 'forgot-password', component: ForgotPasswordComponent },
  { path: 'reset-password', component: ResetPasswordComponent },
  { path: 'productos', loadChildren: () => import('./productos/productos.routes').then(m => m.productosRoutes) },
  { path: 'categorias', loadChildren: () => import('./categorias/categorias.routes').then(m => m.categoriasRoutes) },
  { path: 'proveedores', loadChildren: () => import('./proveedores/proveedores.routes').then(m => m.proveedoresRoutes) },
  { path: 'admin', loadChildren: () => import('./admin.routes').then(m => m.adminRoutes) },
  { path: 'perfil/cambiar-password', component: PerfilCambiarPasswordComponent },
  { path: 'perfil/configuracion', component: PerfilConfiguracionComponent },
  { path: 'categorias/nueva', redirectTo: '/categorias/nuevo', pathMatch: 'full'},
  { path: '**', redirectTo: '', pathMatch: 'full' }
];
