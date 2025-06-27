import { Component, OnInit } from '@angular/core';
import { MatDialog } from '@angular/material/dialog';
import { ChangePasswordModalComponent } from '../change-password-modal/change-password-modal.component';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'app-admin-usuarios',
  templateUrl: './admin-usuarios.component.html',
  styleUrls: ['./admin-usuarios.component.scss']
})
export class AdminUsuariosComponent implements OnInit {
  usuarios: any[] = [];

  constructor(private http: HttpClient, private dialog: MatDialog) {}

  ngOnInit() {
    this.cargarUsuarios();
  }

  cargarUsuarios() {
    this.http.get<any[]>('/api/users').subscribe(data => this.usuarios = data);
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
