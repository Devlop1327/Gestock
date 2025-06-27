import { Component } from '@angular/core';
import { MatSnackBar, MatSnackBarConfig, MatSnackBarModule } from '@angular/material/snack-bar';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from '../../services/auth.service';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatCommonModule } from '@angular/material/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-forgot-password',
  standalone: true,
  imports: [CommonModule, ReactiveFormsModule, MatFormFieldModule, MatInputModule, MatButtonModule, MatCommonModule, MatSnackBarModule],
  templateUrl: './forgot-password.component.html',
  styleUrls: ['./forgot-password.component.scss']
})
export class ForgotPasswordComponent {
  form: FormGroup;
  loading = false;
  message: string | null = null;
  error: string | null = null;

  constructor(private fb: FormBuilder, private authService: AuthService, private router: Router, private snackBar: MatSnackBar) {
    this.form = this.fb.group({
      email: ['', [Validators.required, Validators.email]]
    });
  }

  submit() {
    if (this.form.invalid) return;
    this.loading = true;
    this.message = null;
    this.error = null;
    this.authService.forgotPassword(this.form.value.email).subscribe({
      next: (res: any) => {
        this.openCenteredSnackBar(res.message || '¡Revisa tu correo para el enlace de recuperación!');
        this.loading = false;
      },
      error: err => {
        this.openCenteredSnackBar(err.error?.message || 'No se pudo enviar el correo.');
        this.loading = false;
      }
    });
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

  volverAlLogin() {
    this.router.navigate(['/login']);
  }
}
