import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-bienvenida',
  templateUrl: './bienvenida.component.html',
  styleUrls: ['./bienvenida.component.scss']
})
export class BienvenidaComponent {
  imagenBienvenida = 'assets/images/gs.jpg';

  constructor(private router: Router) {}

  irAGestock() {
    this.router.navigate(['/login']);
  }
}
