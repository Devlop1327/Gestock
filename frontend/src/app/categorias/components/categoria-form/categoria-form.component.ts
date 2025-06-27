import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { CategoriaService } from '../../services/categoria.service';
import { Router, ActivatedRoute } from '@angular/router';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-categoria-form',
  standalone: true,
  templateUrl: './categoria-form.component.html',
  styleUrls: ['./categoria-form.component.scss'],
  imports: [CommonModule, ReactiveFormsModule]
})
export class CategoriaFormComponent implements OnInit {
  categoriaForm: FormGroup;
  submitting = false;
  error = '';
  idCategoria?: number;
  esEdicion = false;

  constructor(
    private fb: FormBuilder,
    private categoriaService: CategoriaService,
    private router: Router,
    private route: ActivatedRoute
  ) {
    this.categoriaForm = this.fb.group({
      nombre: ['', [Validators.required, Validators.maxLength(100)]],
      descripcion: ['', [Validators.maxLength(500)]],
      activo: [true]
    });
  }

  ngOnInit(): void {
    this.route.paramMap.subscribe(params => {
      const id = params.get('id');
      if (id) {
        this.esEdicion = true;
        this.idCategoria = +id;
        this.cargarCategoria(this.idCategoria);
      }
    });
  }

  cargarCategoria(id: number): void {
    this.categoriaService.getCategoria(id).subscribe({
      next: (categoria) => {
        this.categoriaForm.patchValue(categoria);
      },
      error: () => {
        this.error = 'No se pudo cargar la categoría';
      }
    });
  }

  onSubmit(): void {
    if (this.categoriaForm.valid) {
      this.submitting = true;
      this.error = '';
      const categoriaData = { ...this.categoriaForm.value };
      if (this.esEdicion && this.idCategoria) {
        this.categoriaService.updateCategoria(this.idCategoria, categoriaData).subscribe({
          next: () => {
            this.submitting = false;
            this.router.navigate(['/productos']);
          },
          error: (err) => {
            this.submitting = false;
            this.setBackendErrors(err);
          }
        });
      } else {
        this.categoriaService.createCategoria(categoriaData).subscribe({
          next: () => {
            this.submitting = false;
            this.router.navigate(['/productos']);
          },
          error: (err) => {
            this.submitting = false;
            this.setBackendErrors(err);
          }
        });
      }
    } else {
      this.error = 'Por favor, complete todos los campos requeridos correctamente.';
      Object.keys(this.categoriaForm.controls).forEach(key => {
        const control = this.categoriaForm.get(key);
        if (control?.invalid) {
          control.markAsTouched();
        }
      });
    }
  }

  setBackendErrors(err: any) {
    if (err?.error?.errors) {
      const errors = err.error.errors;
      this.error = Object.values(errors).join(' ');
    } else if (err?.error?.message) {
      this.error = err.error.message;
    } else {
      this.error = 'Ocurrió un error inesperado.';
    }
  }

  goBack(): void {
    this.router.navigate(['/productos/nuevo']);
  }

  goToCategorias(): void {
    this.router.navigate(['/categorias/listar']);
  }
}