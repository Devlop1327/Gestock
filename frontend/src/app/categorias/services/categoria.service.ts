import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { Categoria, CategoriaResponse } from '../models/categoria.interface';
import { environment } from '../../../environments/environment';
import { map } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class CategoriaService {
  private apiUrl = `${environment.apiUrl}/categorias`;

  constructor(private http: HttpClient) { }

  getCategorias(): Observable<Categoria[]> {
    return this.http.get<{ data: Categoria[] }>(this.apiUrl).pipe(
      map(response => response.data)
    );
  }

  getCategoria(id: number): Observable<Categoria> {
    return this.http.get<{ data: Categoria }>(`${this.apiUrl}/${id}`).pipe(
      map(response => response.data)
    );
  }

  createCategoria(categoria: Categoria): Observable<Categoria> {
    return this.http.post<Categoria>(this.apiUrl, categoria);
  }

  updateCategoria(id: number, categoria: Categoria): Observable<Categoria> {
    return this.http.put<{ data: Categoria }>(`${this.apiUrl}/${id}`, categoria).pipe(
      map(response => response.data)
    );
  }

  deleteCategoria(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }
} 