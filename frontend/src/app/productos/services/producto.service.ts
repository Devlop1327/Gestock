import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Producto, ProductoResponse } from '../models/producto.interface';
import { environment } from '../../../environments/environment';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class ProductoService {
  private apiUrl = `${environment.apiUrl}/productos`;

  paginaActual: number = 1;
  totalPaginas: number = 1;
  totalProductos: number = 0;
  productos: Producto[] = [];

  constructor(private http: HttpClient) { }

  getProductos(): Observable<Producto[]> {
    return this.http.get<{ data: { data: Producto[] } }>(this.apiUrl).pipe(
      map(response => response.data.data)
    );
  }

  getProducto(id: number): Observable<Producto> {
    return this.http.get<{ data: Producto }>(`${this.apiUrl}/${id}`).pipe(
      map(response => response.data)
    );
  }

  createProducto(producto: Producto): Observable<Producto> {
    return this.http.post<{ data: Producto }>(this.apiUrl, producto).pipe(
      map(response => response.data)
    );
  }

  updateProducto(id: number, producto: Producto): Observable<Producto> {
    return this.http.put<{ data: Producto }>(`${this.apiUrl}/${id}`, producto).pipe(
      map(response => response.data)
    );
  }

  deleteProducto(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }

  getProductosConFiltros(filtros: any): Observable<any> {
    return this.http.get<any>(this.apiUrl, { params: filtros });
  }
}