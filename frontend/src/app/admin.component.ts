import { Component, OnInit } from '@angular/core';
import { RouterModule } from '@angular/router';
import { NgIf } from '@angular/common';
import { AdminService } from './admin/services/admin.service';

@Component({
  selector: 'app-admin',
  standalone: true,
  imports: [RouterModule, NgIf],
  templateUrl: './admin/admin.component.html',
  styleUrls: ['./admin/admin.component.scss']
})
export class AdminComponent implements OnInit {
  mensaje: string | null = null;
  loading = false;

  constructor(private adminService: AdminService) {}

  ngOnInit() {
    this.adminService.limpiarCache().subscribe();
  }

  limpiarCache() {
    this.loading = true;
    this.adminService.limpiarCache().subscribe({
      next: (res) => {
        this.mensaje = res.message || 'Caché limpiada correctamente.';
        this.loading = false;
        setTimeout(() => this.mensaje = null, 3000);
      },
      error: () => {
        this.mensaje = 'Error al limpiar la caché.';
        this.loading = false;
        setTimeout(() => this.mensaje = null, 3000);
      }
    });
  }
}