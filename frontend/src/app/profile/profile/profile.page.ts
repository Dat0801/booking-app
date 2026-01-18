import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { IonButton, IonContent, IonHeader, IonItem, IonLabel, IonList, IonTitle, IonToolbar } from '@ionic/angular/standalone';
import { AuthService, AuthUser } from '../../core/services/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.page.html',
  styleUrls: ['./profile.page.scss'],
  standalone: true,
  imports: [IonContent, IonHeader, IonTitle, IonToolbar, IonList, IonItem, IonLabel, IonButton, CommonModule]
})
export class ProfilePage implements OnInit {
  user: AuthUser | null = null;
  loading = false;

  private auth = inject(AuthService);
  private router = inject(Router);

  ngOnInit(): void {
    const current = this.auth.getUser();
    if (current) {
      this.user = current;
      return;
    }

    this.loading = true;
    this.auth.fetchCurrentUser().subscribe({
      next: user => {
        this.user = user;
        this.loading = false;
      },
      error: () => {
        this.loading = false;
      }
    });
  }

  async logout(): Promise<void> {
    await this.auth.logout();
    await this.router.navigateByUrl('/login', { replaceUrl: true });
  }
}
