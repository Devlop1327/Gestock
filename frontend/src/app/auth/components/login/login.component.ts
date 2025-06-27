import { Component } from '@angular/core';
import { MatSnackBar, MatSnackBarConfig, MatSnackBarModule } from '@angular/material/snack-bar';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router, RouterModule } from '@angular/router';
import { AuthService } from '../../services/auth.service';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, RouterModule, MatSnackBarModule],
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent {
  loginForm: FormGroup;
  submitting = false;
  error = '';

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private router: Router,
    private snackBar: MatSnackBar
  ) {
    this.loginForm = this.fb.group({
      email: ['', [Validators.required, Validators.email]],
      password: ['', Validators.required],
      remember: [false]
    });
  }

  onSubmit(): void {
    if (this.loginForm.valid) {
      this.submitting = true;
      this.error = '';

      this.authService.login(this.loginForm.value).subscribe({
        next: (response: any) => {
          if (response?.data?.token) {
            localStorage.setItem('auth_token', response.data.token);
            this.authService['currentUserSubject'].next(response.data.user);
            this.openCenteredSnackBar('Inicio de sesión exitoso.');
            setTimeout(() => this.router.navigate(['/productos']), 1500);
          } else {
            this.openCenteredSnackBar('Respuesta inesperada del servidor.');
          }
        },
        error: (err) => {
          console.error('Error de login:', err);
          this.openCenteredSnackBar(err.error?.message || 'Error al iniciar sesión. Por favor, verifique sus credenciales.');
          this.submitting = false;
        },
        complete: () => {
          this.submitting = false;
        }
      });
    } else {
      this.openCenteredSnackBar('Por favor, complete todos los campos correctamente.');
      Object.keys(this.loginForm.controls).forEach(key => {
        const control = this.loginForm.get(key);
        if (control?.invalid) {
          control.markAsTouched();
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