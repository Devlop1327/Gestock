import { Routes } from '@angular/router';

export const productosRoutes: Routes = [
  {
    path: '',
    redirectTo: 'listar',
    pathMatch: 'full'
  },
  {
    path: 'listar',
    loadComponent: () => import('./components/producto-list/producto-list.component').then(m => m.ProductoListComponent)
  },
  {
    path: 'nuevo',
    loadComponent: () => import('./components/producto-form/producto-form.component').then(m => m.ProductoFormComponent)
  },
  {
    path: 'editar/:id',
    loadComponent: () => import('./components/producto-form/producto-form.component').then(m => m.ProductoFormComponent)
  },
  {
    path: 'ver/:id',
    loadComponent: () => import('./components/producto-detail/producto-detail.component').then(m => m.ProductoDetailComponent)
  }
]; 