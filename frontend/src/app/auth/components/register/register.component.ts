import { Component, OnInit } from '@angular/core';
import { MatSnackBar, MatSnackBarConfig, MatSnackBarModule } from '@angular/material/snack-bar';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from '../../services/auth.service';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../../environments/environment';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss'],
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, MatSnackBarModule]
})
export class RegisterComponent implements OnInit {
  registerForm: FormGroup;
  roles: any[] = [];

  constructor(
    private fb: FormBuilder,
    private router: Router,
    private authService: AuthService,
    private http: HttpClient,
    private snackBar: MatSnackBar
  ) {
    this.registerForm = this.fb.group({
      name: ['', Validators.required],
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required],
      password_confirmation: ['', Validators.required],
      role_id: ['']
    });
  }

  ngOnInit() {
    this.http.get<any[]>(`${environment.apiUrl}/roles`).subscribe({
      next: (data) => {
        this.roles = data;
        const admin = this.roles.find(r => r.nombre === 'Administrador');
        if (admin) {
          this.registerForm.patchValue({ role_id: admin.id });
        }
      },
      error: () => this.roles = []
    });
  }

  onSubmit() {
    if (this.registerForm.valid) {
      const data = {
        ...this.registerForm.value,
        role_id: Number(this.registerForm.value.role_id)
      };
      this.authService.register(data).subscribe({
        next: () => {
          this.openCenteredSnackBar('Registro exitoso. Ahora puedes iniciar sesión.');
          setTimeout(() => this.router.navigate(['/login']), 2000);
        },
        error: (err) => {
          console.error('Error al registrar:', err);
          this.openCenteredSnackBar(err.error?.message || 'Error al registrar');
        }
      });
    }
  }

  openCenteredSnackBar(message: string) {
    const config: MatSnackBarConfig = {
      duration: 2500,
      horizontalPosition: 'center',
      verticalPosition: 'bottom',
      panelClass: ['centered-snackbar']
    };
    this.snackBar.open(`Gestock: ${message}`, undefined, config);
  }
}