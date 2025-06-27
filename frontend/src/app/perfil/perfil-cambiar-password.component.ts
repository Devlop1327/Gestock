import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormBuilder, FormGroup, Validators, ReactiveFormsModule } from '@angular/forms';
import { AuthService } from '../auth/services/auth.service';
import { MatSnackBar, MatSnackBarModule } from '@angular/material/snack-bar';
import { HttpClient } from '@angular/common/http';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';

@Component({
  selector: 'app-perfil-cambiar-password',
  templateUrl: './perfil-cambiar-password.component.html',
  styleUrls: ['./perfil-cambiar-password.component.scss'],
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatSnackBarModule
  ]
})
export class PerfilCambiarPasswordComponent {
  form: FormGroup;
  loading = false;
  error: string | null = null;
  success: string | null = null;

  constructor(
    private fb: FormBuilder,
    private authService: AuthService,
    private http: HttpClient,
    private snackBar: MatSnackBar
  ) {
    this.form = this.fb.group({
      current_password: ['', [Validators.required]],
      password: ['', [Validators.required, Validators.minLength(8)]],
      password_confirmation: ['', [Validators.required]]
    }, { validator: this.passwordsMatchValidator });
  }

  passwordsMatchValidator(form: FormGroup) {
    return form.get('password')!.value === form.get('password_confirmation')!.value ? null : { mismatch: true };
  }

  submit() {
    if (this.form.invalid) return;
    this.loading = true;
    this.error = null;
    this.success = null;
    this.http.post('/api/user/change-password', {
      current_password: this.form.value.current_password,
      password: this.form.value.password,
      password_confirmation: this.form.value.password_confirmation
    }).subscribe({
      next: (res: any) => {
        this.success = res.message;
        this.snackBar.open('Contraseña actualizada correctamente', '', { duration: 2500 });
        this.loading = false;
        this.form.reset();
      },
      error: err => {
        this.error = err.error?.message || 'Error al cambiar la contraseña';
        this.snackBar.open(this.error || 'Error al cambiar la contraseña', '', { duration: 2500 });
        this.loading = false;
      }
    });
  }
}
