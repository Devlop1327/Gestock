import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { AppComponent } from './app.component';
import { HttpClientModule } from '@angular/common/http';
import { ReactiveFormsModule } from '@angular/forms';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatDialogModule } from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatMenuModule } from '@angular/material/menu';
import { AdminUsuariosComponent } from './admin-usuarios/admin-usuarios.component';
import { ChangePasswordModalComponent } from './change-password-modal/change-password-modal.component';
import { ForgotPasswordComponent } from './auth/components/forgot-password/forgot-password.component';
import { BienvenidaComponent } from './bienvenida/bienvenida.component';
import { PerfilConfiguracionComponent } from './perfil/perfil-configuracion.component';
import { RouterModule } from '@angular/router';
import { routes } from './app.routes';

@NgModule({
  declarations: [
    AppComponent,
    AdminUsuariosComponent,
    ChangePasswordModalComponent,
    BienvenidaComponent,
    PerfilConfiguracionComponent
  ],
  imports: [
    BrowserModule,
    HttpClientModule,
    ReactiveFormsModule,
    BrowserAnimationsModule,
    MatDialogModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatMenuModule,
    RouterModule.forRoot(routes)
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }