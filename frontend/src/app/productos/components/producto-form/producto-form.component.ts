import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ProductoService } from '../../services/producto.service';
import { CategoriaService } from '../../../categorias/services/categoria.service';
import { ProveedorService } from '../../../proveedores/services/proveedor.service';
import { Router, ActivatedRoute } from '@angular/router';
import { AuthService } from '../../../auth/services/auth.service';

@Component({
  selector: 'app-producto-form',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './producto-form.component.html',
  styleUrls: ['./producto-form.component.scss']
})
export class ProductoFormComponent implements OnInit {
  categorias: any[] = [];
  proveedores: any[] = [];

  nuevaCategoria() {
    this.router.navigate(['/categorias/nuevo']);
  }

  nuevoProveedor() {
    this.router.navigate(['/proveedores/nuevo']);
  }
  productoForm: FormGroup;
  submitting = false;
  error = '';
  idProducto?: number;
  esEdicion = false;
  esAdmin = false;

  constructor(
    private fb: FormBuilder,
    private productoService: ProductoService,
    private categoriaService: CategoriaService,
    private proveedorService: ProveedorService,
    private router: Router,
    private authService: AuthService,
    private route: ActivatedRoute
  ) {
    this.productoForm = this.fb.group({
      codigo: ['', [Validators.required]],
      nombre: ['', [Validators.required]],
      descripcion: ['', [Validators.required]],
      precio: [null, [Validators.required, Validators.min(0), Validators.max(1000000)]],
      stock: [null, [Validators.required, Validators.min(0)]],
      stock_minimo: [null, [Validators.required, Validators.min(0)]],
      categoria_id: [null, [Validators.required]],
      proveedor_id: [null, [Validators.required]],
      activo: [true]
    });
  }

  ngOnInit(): void {
    const user = this.authService.currentUserValue;
    this.esAdmin = !!(user && user.role_id === 1);

    // Fetch categories
    this.categoriaService.getCategorias().subscribe({
      next: (categorias) => {
        this.categorias = categorias;
      },
      error: () => {
        this.categorias = [];
      }
    });

    // Fetch providers
    this.proveedorService.getProveedores().subscribe({
      next: (proveedores) => {
        this.proveedores = proveedores;
      },
      error: () => {
        this.proveedores = [];
      }
    });

    this.route.paramMap.subscribe(params => {
      const id = params.get('id');
      if (id) {
        this.esEdicion = true;
        this.idProducto = +id;
        this.cargarProducto(this.idProducto);
      }
    });
  }

  cargarProducto(id: number): void {
    this.productoService.getProducto(id).subscribe({
      next: (producto) => {
        this.productoForm.patchValue(producto);
      },
      error: (error) => {
        this.error = 'No se pudo cargar el producto';
      }
    });
  }

  onSubmit(): void {
    if (this.productoForm.valid) {
      if (!this.authService.isAuthenticated()) {
        this.error = 'Debes estar autenticado para crear un producto';
        return;
      }
      this.submitting = true;
      this.error = '';
      const productoData = {
        ...this.productoForm.value,
        precio: Number(this.productoForm.value.precio),
        stock: Number(this.productoForm.value.stock),
        stock_minimo: Number(this.productoForm.value.stock_minimo),
        categoria_id: Number(this.productoForm.value.categoria_id),
        proveedor_id: Number(this.productoForm.value.proveedor_id)
      };
      if (this.esEdicion && this.idProducto) {
        this.productoService.updateProducto(this.idProducto, productoData).subscribe({
          next: (response) => {
            this.submitting = false;
            this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
              this.router.navigate(['/productos']);
            });
          },
          error: (error) => {
            this.submitting = false;
            if (error?.error?.message) {
              this.error = error.error.message;
            } else if (error?.error?.errors) {
              const firstKey = Object.keys(error.error.errors)[0];
              this.error = error.error.errors[firstKey][0];
            } else {
              this.error = 'Error al actualizar el producto';
            }
          }
        });
      } else {
        this.productoService.createProducto(productoData).subscribe({
          next: (response) => {
            this.submitting = false;
            this.router.navigateByUrl('/', { skipLocationChange: true }).then(() => {
              this.router.navigate(['/productos']);
            });
          },
          error: (error) => {
            this.submitting = false;
            if (error?.error?.message) {
              this.error = error.error.message;
            } else if (error?.error?.errors) {
              const firstKey = Object.keys(error.error.errors)[0];
              this.error = error.error.errors[firstKey][0];
            } else {
              this.error = 'Error al crear el producto';
            }
          }
        });
      }
    } else {
      this.error = 'Por favor, complete todos los campos requeridos correctamente.';
      Object.keys(this.productoForm.controls).forEach(key => {
        const control = this.productoForm.get(key);
        if (control?.invalid) {
          control.markAsTouched();
        }
      });
    }
  }

  goBack(): void {
    this.router.navigate(['/productos']);
  }

  editarProducto(id: number): void {
    this.router.navigate(['/productos/editar', id]);
  }
}