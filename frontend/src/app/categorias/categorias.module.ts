import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { categoriasRoutes } from './categorias.routes';

@NgModule({
  declarations: [],
  imports: [
    CommonModule,
    RouterModule.forChild(categoriasRoutes),
    ReactiveFormsModule,
    HttpClientModule
  ]
})
export class CategoriasModule { } 