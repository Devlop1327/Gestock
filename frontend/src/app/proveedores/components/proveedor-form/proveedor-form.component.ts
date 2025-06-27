import { Component, OnInit, Inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ProveedorService } from '../../services/proveedor.service';
import { Proveedor } from '../../models/proveedor.interface';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-proveedor-form',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule],
  templateUrl: './proveedor-form.component.html',
  styleUrls: ['./proveedor-form.component.scss']
})
export class ProveedorFormComponent implements OnInit {
  proveedorForm: FormGroup;
  submitting = false;
  error = '';
  idProveedor?: number;
  esEdicion = false;

  constructor(
    private fb: FormBuilder,
    @Inject(ProveedorService) private proveedorService: ProveedorService,
    private router: Router,
    private route: ActivatedRoute
  ) {
    this.proveedorForm = this.fb.group({
      nombre: ['', [Validators.required]],
      nit: ['', [Validators.required]],
      direccion: ['', [Validators.required]],
      telefono: ['', [Validators.required]],
      email: ['', [Validators.required, Validators.email]],
      contacto: [''],
      activo: [true]
    });
  }

  ngOnInit(): void {
    this.route.paramMap.subscribe(params => {
      const id = params.get('id');
      if (id) {
        this.esEdicion = true;
        this.idProveedor = +id;
        this.cargarProveedor(this.idProveedor);
      }
    });
  }

  cargarProveedor(id: number): void {
    this.proveedorService.getProveedor(id).subscribe({
      next: (proveedor: Proveedor) => {
        this.proveedorForm.patchValue(proveedor);
      },
      error: () => {
        this.error = 'No se pudo cargar el proveedor';
      }
    });
  }

  onSubmit(): void {
    if (this.proveedorForm.valid) {
      this.submitting = true;
      this.error = '';
      const proveedorData = { ...this.proveedorForm.value };
      if (this.esEdicion && this.idProveedor) {
        this.proveedorService.updateProveedor(this.idProveedor, proveedorData).subscribe({
          next: () => {
            this.submitting = false;
            this.router.navigate(['/productos']); 
            },
          error: () => {
            this.submitting = false;
            this.error = 'Error al actualizar el proveedor';
          }
        });
      } else {
        this.proveedorService.createProveedor(proveedorData).subscribe({
          next: () => {
            this.submitting = false;
            this.router.navigate(['/productos']); 
          },
          error: () => {
            this.submitting = false;
            this.error = 'Error al crear el proveedor';
          }
        });
      }
    } else {
      this.error = 'Por favor, complete todos los campos requeridos correctamente.';
      Object.keys(this.proveedorForm.controls).forEach(key => {
        const control = this.proveedorForm.get(key);
        if (control?.invalid) {
          control.markAsTouched();
        }
      });
    }
  }

  goBack(): void {
    this.router.navigate(['/productos/nuevo']);
  }
}