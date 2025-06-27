import { Routes } from '@angular/router';

export const proveedoresRoutes: Routes = [
  {
    path: '',
    redirectTo: 'listar',
    pathMatch: 'full'
  },
  {
    path: 'listar',
    loadComponent: () => import('./components/proveedor-list/proveedor-list.component').then(m => m.ProveedorListComponent)
  },
  {
    path: 'nuevo',
    loadComponent: () => import('./components/proveedor-form/proveedor-form.component').then(m => m.ProveedorFormComponent)
  },
  {
    path: 'editar/:id',
    loadComponent: () => import('./components/proveedor-form/proveedor-form.component').then(m => m.ProveedorFormComponent)
  }
];
