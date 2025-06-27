import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClient } from '@angular/common/http';
import { environment } from '../environments/environment';
import { MatDialog } from '@angular/material/dialog';
import { ChangePasswordModalComponent } from './change-password-modal/change-password-modal.component';

@Component({
  selector: 'app-admin-usuarios',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './admin-usuarios/admin-usuarios.component.html',
  styleUrls: ['./admin-usuarios/admin-usuarios.component.scss']
})
export class AdminUsuariosComponent implements OnInit {
  usuarios: any[] = [];
  constructor(private http: HttpClient, private dialog: MatDialog) {}
  ngOnInit() {
    this.http.get<any>(`${environment.apiUrl}/usuarios`).subscribe({
      next: (response) => {
        if (response.success) {
          this.usuarios = response.usuarios;
        } else {
          this.usuarios = [];
        }
      },
      error: () => {
        this.usuarios = [];
      }
    });
  }

  abrirModalCambioClave(usuario: any) {
    const dialogRef = this.dialog.open(ChangePasswordModalComponent, {
      width: '400px',
      data: { userId: usuario.id, userName: usuario.name }
    });
    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        // Opcional: recargar usuarios o mostrar notificación
      }
    });
  }
}