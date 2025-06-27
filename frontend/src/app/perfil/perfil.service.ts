import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../environments/environment';

@Injectable({ providedIn: 'root' })
export class PerfilService {
  constructor(private http: HttpClient) {}

  actualizarPerfil(data: { name: string; email: string }): Observable<any> {
    return this.http.put(`${environment.apiUrl}/user`, data);
  }
}
