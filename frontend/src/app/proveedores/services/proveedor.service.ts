import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Proveedor } from '../models/proveedor.interface';
import { environment } from '../../../environments/environment';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class ProveedorService {
  private apiUrl = `${environment.apiUrl}/proveedores`;

  constructor(private http: HttpClient) { }

  getProveedores(): Observable<Proveedor[]> {
    return this.http.get<{ data: Proveedor[] }>(this.apiUrl).pipe(
      map(response => response.data)
    );
  }

  getProveedor(id: number): Observable<Proveedor> {
    return this.http.get<{ data: Proveedor }>(`${this.apiUrl}/${id}`).pipe(
      map(response => response.data)
    );
  }

  createProveedor(proveedor: Proveedor): Observable<Proveedor> {
    return this.http.post<Proveedor>(this.apiUrl, proveedor);
  }

  updateProveedor(id: number, proveedor: Proveedor): Observable<Proveedor> {
    return this.http.put<{ data: Proveedor }>(`${this.apiUrl}/${id}`, proveedor).pipe(
      map(response => response.data)
    );
  }

  deleteProveedor(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }
} 