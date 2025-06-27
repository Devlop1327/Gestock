import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ProductoService } from '../../services/producto.service';
import { Producto } from '../../models/producto.interface';
import { CategoriaService } from '../../../categorias/services/categoria.service';
import { ProveedorService } from '../../../proveedores/services/proveedor.service';
import { CurrencyPipe } from '@angular/common';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-producto-detail',
  standalone: true,
imports: [CommonModule],
  templateUrl: './producto-detail.component.html',
  styleUrls: ['./producto-detail.component.scss']
})
export class ProductoDetailComponent implements OnInit {
  producto?: Producto;
  categorias: any[] = [];
  proveedores: any[] = [];
  loading = true;
  errorMsg: string | null = null;

  constructor(
    private route: ActivatedRoute,
    private productoService: ProductoService,
    private categoriaService: CategoriaService,
    private proveedorService: ProveedorService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.categoriaService.getCategorias().subscribe({
      next: (cats) => this.categorias = cats,
      error: () => this.categorias = []
    });
    this.proveedorService.getProveedores().subscribe({
      next: (provs) => this.proveedores = provs,
      error: () => this.proveedores = []
    });
    const id = this.route.snapshot.paramMap.get('id');
    if (id) {
      this.productoService.getProducto(+id).subscribe({
        next: (prod) => {
          this.producto = prod;
          this.loading = false;
          this.errorMsg = null;
        },
        error: (err) => {
          this.producto = undefined;
          this.loading = false;
          this.errorMsg = err.status === 404 ? 'Producto no encontrado.' : 'Error al cargar el producto.';
        }
      });
    } else {
      this.loading = false;
      this.errorMsg = 'ID de producto inválido.';
    }
  }

  goBack(): void {
    this.router.navigate(['/productos']);
  }

  getCategoriaNombre(categoriaId: number): string {
    const categoria = this.categorias.find(cat => cat.id === categoriaId);
    return categoria ? categoria.nombre : '-';
  }

  getProveedorNombre(proveedorId: number): string {
    const proveedor = this.proveedores.find(prov => prov.id === proveedorId);
    return proveedor ? proveedor.nombre : '-';
  }
} 