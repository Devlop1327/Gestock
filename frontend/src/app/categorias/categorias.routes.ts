import { Routes } from '@angular/router';

export const categoriasRoutes: Routes = [
  {
    path: '',
    redirectTo: 'listar',
    pathMatch: 'full'
  },
  {
    path: 'listar',
    loadComponent: () => import('./components/categoria-list/categoria-list.component').then(m => m.CategoriaListComponent)
  },
  {
    path: 'nuevo',
    loadComponent: () => import('./components/categoria-form/categoria-form.component').then(m => m.CategoriaFormComponent)
  },
  {
    path: 'editar/:id',
    loadComponent: () => import('./components/categoria-form/categoria-form.component').then(m => m.CategoriaFormComponent)
  }
];