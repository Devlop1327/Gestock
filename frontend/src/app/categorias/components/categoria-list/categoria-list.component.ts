import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { CategoriaService } from '../../services/categoria.service';
import { Categoria } from '../../models/categoria.interface';
import { Router } from '@angular/router';

@Component({
  selector: 'app-categoria-list',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './categoria-list.component.html',
  styles: []
})
export class CategoriaListComponent implements OnInit {
  categorias: Categoria[] = [];
  loading = true;
  eliminandoId: number | null = null;

  constructor(
    private categoriaService: CategoriaService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.loadCategorias();
  }

  loadCategorias(): void {
    this.categoriaService.getCategorias().subscribe({
      next: (response: any) => {
        this.categorias = response.data ? response.data : response;
        this.loading = false;
      },
      error: () => {
        this.loading = false;
      }
    });
  }

  nuevaCategoria(): void {
    this.router.navigate(['/categorias/nuevo']);
  }

  editarCategoria(id: number): void {
    this.router.navigate(['/categorias/editar', id]);
  }

  eliminarCategoria(id: number): void {
    if (confirm('¿Estás seguro de que deseas eliminar esta categoría?')) {
      this.eliminandoId = id;
      this.categoriaService.deleteCategoria(id).subscribe({
        next: () => {
          this.loadCategorias();
          this.eliminandoId = null;
        },
        error: () => {
          alert('Error al eliminar la categoría.');
          this.eliminandoId = null;
        }
      });
    }
  }

  volverAProductos(): void {
    this.router.navigate(['/productos/listar']);
  }
}