import { Component } from '@angular/core';
import { RouterOutlet, Router, NavigationEnd } from '@angular/router';
import { NgIf } from '@angular/common';
import { NavbarComponent } from './shared/navbar/navbar.component';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [RouterOutlet, NgIf, NavbarComponent],
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'inventario-app';
  showNavbar = false;

  constructor(private router: Router) {
    this.router.events.subscribe(event => {
      if (event instanceof NavigationEnd) {
        const url = event.urlAfterRedirects;
        this.showNavbar = this.shouldShowNavbar(url);
      }
    });
    const url = this.router.url;
    this.showNavbar = this.shouldShowNavbar(url);
  }

  shouldShowNavbar(url: string): boolean {
    const isAuthenticated = !!localStorage.getItem('auth_token');
    return isAuthenticated;
  }
}
