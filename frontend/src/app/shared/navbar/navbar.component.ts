import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { RouterModule, Router, NavigationEnd } from '@angular/router';
import { ProductoService } from '../../productos/services/producto.service';
import { ProveedorService } from '../../proveedores/services/proveedor.service';
import { CategoriaService } from '../../categorias/services/categoria.service';

import { Producto } from '../../productos/models/producto.interface';
import { Proveedor } from '../../proveedores/models/proveedor.interface';
import { Categoria } from '../../categorias/models/categoria.interface';
import { filter } from 'rxjs/operators';
import { AdminService } from '../../admin/services/admin.service';
import { MatSnackBar } from '@angular/material/snack-bar';
import { MatButtonModule } from '@angular/material/button';
import { MatMenuModule } from '@angular/material/menu';


@Component({
  selector: 'app-navbar',
  standalone: true,
  imports: [
    RouterModule,
    CommonModule,
    FormsModule,
    MatButtonModule,
    MatMenuModule
  ],
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss']
})

export class NavbarComponent implements OnInit {
  isAdmin = false;
  busqueda: string = '';
  filtroProveedor: string = '';
  filtroCategoria: string = '';
  proveedores: Proveedor[] = [];
  categorias: Categoria[] = [];
  productos: Producto[] = [];
  mostrarNavbar: boolean = true;

  constructor(
    private router: Router,
    private productoService: ProductoService,
    private proveedorService: ProveedorService,
    private categoriaService: CategoriaService,
    private adminService: AdminService,
    private snackBar: MatSnackBar
  ) {}
  limpiarCacheDesdeNavbar() {
    this.adminService.limpiarCache().subscribe({
      next: (res) => {
        this.snackBar.open(res.message || 'Caché limpiada correctamente.', '', { duration: 2500 });
      },
      error: () => {
        this.snackBar.open('Error al limpiar la caché.', '', { duration: 2500 });
      }
    });
  }

  ngOnInit() {
    this.cargarUsuarioYListas();
    this.actualizarVisibilidadNavbar();
    this.router.events.pipe(
      filter(event => event instanceof NavigationEnd)
    ).subscribe(() => {
      this.cargarUsuarioYListas();
      this.actualizarVisibilidadNavbar();
    });
  }

  private actualizarVisibilidadNavbar() {
    this.mostrarNavbar = this.router.url !== '/bienvenida';
  }

  private cargarUsuarioYListas() {
    const user = localStorage.getItem('user');
    if (user) {
      try {
        const userObj = JSON.parse(user);
        this.isAdmin = userObj.id === 1;
      } catch {
        this.isAdmin = false;
      }
    }
    this.proveedorService.getProveedores().subscribe({
      next: (provs) => this.proveedores = provs,
      error: () => this.proveedores = []
    });
    this.categoriaService.getCategorias().subscribe({
      next: (cats) => this.categorias = cats,
      error: () => this.categorias = []
    });
  }

  buscarProducto() {
    const queryParams: any = {};
    if (this.busqueda && this.busqueda.trim() !== '') {
      queryParams.search = this.busqueda.trim(); 
    }
    this.router.navigate(['/productos/listar'], { queryParams });
  }

  filtrarPorProveedor() {
  }

  filtrarPorCategoria() {
  }

  logout() {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('user');
    this.router.navigate(['/bienvenida']);
  }
}
