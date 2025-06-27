import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable, tap, of } from 'rxjs'; // Importar 'of'
import { catchError, map } from 'rxjs/operators'; // Importar 'map' y 'catchError'
import { LoginRequest, LoginResponse, User, RegisterRequest } from '../models/auth.interface';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private currentUserSubject: BehaviorSubject<User | null>;
  public currentUser: Observable<User | null>;
  private tokenKey = 'auth_token';

  constructor(private http: HttpClient) {
    // Inicializamos con null, y luego intentamos cargar el usuario si el token es válido
    this.currentUserSubject = new BehaviorSubject<User | null>(null);
    this.currentUser = this.currentUserSubject.asObservable();

    if (this.token) {
      this.fetchCurrentUser().subscribe(user => {
        this.currentUserSubject.next(user);
      });
    }
  }

  public get currentUserValue(): User | null {
    return this.currentUserSubject.value;
  }

  public get token(): string | null {
    const token = localStorage.getItem(this.tokenKey);
    if (token && this.isTokenValid(token)) {
      return token;
    }
    if (this.currentUserSubject.value) {
      this.logout();
    }
    return null;
  }

  public isAuthenticated(): boolean {
    const token = this.token;
    console.log('Token actual:', token ? 'Presente' : 'Ausente');
    return token !== null;
  }

  login(credentials: LoginRequest): Observable<LoginResponse> {
    return this.http.post<LoginResponse>(`${environment.apiUrl}/login`, credentials)
      .pipe(
        tap(response => {
          if (response.success && response.data.token) {
            localStorage.setItem(this.tokenKey, response.data.token);
            this.fetchCurrentUser().subscribe(user => {
                this.currentUserSubject.next(user);
            });
          }
        })
      );
  }

  logout(): void {
    console.log('Cerrando sesión...');
    localStorage.removeItem(this.tokenKey);
    this.currentUserSubject.next(null);
  }

  private fetchCurrentUser(): Observable<User | null> {
    const token = this.token;
    if (!token) {
      return of(null);
    }

    // Realiza la llamada a la API para obtener los datos del usuario
    return this.http.get<User>(`${environment.apiUrl}/user`).pipe(
      map(user => {
        return user;
      }), 
      catchError(error => {
        console.error('[AuthService] Error al obtener datos del usuario:', error);
        this.logout(); 
        return of(null);
      })
    );
  }

  // Este método getUserData() ahora llamará a fetchCurrentUser para obtener los datos del usuario,
  // y lo manejaremos asíncronamente.
  // Sin embargo, para getUserRole, que espera un User sincrono, tendremos que ajustar.
  // Por ahora, para que compile y funcione, vamos a hacer una versión sincrona de getUserData
  // que simplemente devuelve el valor actual del BehaviorSubject.
  // La llamada asíncrona la haremos al inicio del servicio y en el login.
  private getUserData(): User | null {
    return this.currentUserSubject.value;
  }
  // **** FIN NUEVO MÉTODO AGREGADO O MODIFICADO ****


  // Mantenemos getUserFromStorage con su lógica original, ya que no decodifica el token.
  // La lógica para cargar el usuario se mueve a fetchCurrentUser.
  private getUserFromStorage(): User | null {
    const token = localStorage.getItem(this.tokenKey);
    // console.log('Token en almacenamiento:', token ? 'Presente' : 'Ausente');
    if (token && this.isTokenValid(token)) {
        
        return null;
    }
    return null;
  }

  private isTokenValid(token: string): boolean {
    return !!token;
  }

  register(data: RegisterRequest): Observable<LoginResponse> {
    return this.http.post<LoginResponse>(`${environment.apiUrl}/register`, data);
  }

  getUserRole(): string {
    
    const user = this.currentUserSubject.value; 
    return user?.role?.nombre || 'user';
  }

  /**
   * Envía el email para recuperación de contraseña
   */
  forgotPassword(email: string) {
    
    return this.http.post(`${environment.apiUrl}/forgot-password`, { email });
  }

  
  resetPassword(data: { email: string; token: string; password: string; password_confirmation: string }) {
    return this.http.post(`${environment.apiUrl}/reset-password`, data);
  }
}