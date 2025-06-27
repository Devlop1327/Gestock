import { AdminService } from '../admin/services/admin.service';
import { Component } from '@angular/core';
import { PerfilService } from './perfil.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';

@Component({
  selector: 'app-perfil-configuracion',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule
  ],
  templateUrl: './perfil-configuracion.component.html',
  styleUrls: ['./perfil-configuracion.component.scss']
})
export class PerfilConfiguracionComponent {
  user: any = {};
  temaOscuro = false;
  guardando = false;

  constructor(private perfilService: PerfilService, private adminService: AdminService) {
    const userStr = localStorage.getItem('user');
    if (userStr) {
      this.user = JSON.parse(userStr);
    }
    const tema = localStorage.getItem('temaOscuro');
    this.temaOscuro = tema === 'true';
    this.actualizarTema();
  }
  limpiarCache() {
    this.adminService.limpiarCache().subscribe({
      next: (res) => {
        alert(res.message || 'Caché limpiada correctamente.');
      },
      error: () => {
        alert('Error al limpiar la caché.');
      }
    });
  }

  guardar() {
    this.guardando = true;
    this.perfilService.actualizarPerfil({ name: this.user.name, email: this.user.email }).subscribe({
      next: (res) => {
        localStorage.setItem('user', JSON.stringify(this.user));
        alert('Datos actualizados correctamente.');
        this.guardando = false;
      },
      error: (err) => {
        alert('Error al actualizar los datos.');
        this.guardando = false;
      }
    });
  }

  cambiarTema() {
    this.temaOscuro = !this.temaOscuro;
    localStorage.setItem('temaOscuro', this.temaOscuro + '');
    this.actualizarTema();
  }

  actualizarTema() {
    if (this.temaOscuro) {
      document.body.classList.add('dark-theme');
    } else {
      document.body.classList.remove('dark-theme');
    }
  }

  esAdmin1() {
    return this.user && this.user.id === 1;
  }
}
