import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormBuilder, FormGroup, Validators } from '@angular/forms';
import {
  IonButton,
  IonContent,
  IonHeader,
  IonInput,
  IonItem,
  IonLabel,
  IonText,
  IonTitle,
  IonToolbar
} from '@ionic/angular/standalone';
import { Router, RouterLink } from '@angular/router';
import { LoadingController, ToastController } from '@ionic/angular';
import { AuthService } from '../../core/services/auth.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.page.html',
  styleUrls: ['./register.page.scss'],
  standalone: true,
  imports: [
    IonButton,
    IonContent,
    IonHeader,
    IonInput,
    IonItem,
    IonLabel,
    IonText,
    IonTitle,
    IonToolbar,
    CommonModule,
    ReactiveFormsModule,
    RouterLink
  ]
})
export class RegisterPage implements OnInit {
  form!: FormGroup;
  submitting = false;

  private fb = inject(FormBuilder);
  private auth = inject(AuthService);
  private router = inject(Router);
  private loadingCtrl = inject(LoadingController);
  private toastCtrl = inject(ToastController);

  ngOnInit(): void {
    this.form = this.fb.group({
      name: ['', [Validators.required, Validators.maxLength(255)]],
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]]
    });
  }

  async onSubmit(): Promise<void> {
    if (this.form.invalid || this.submitting) {
      this.form.markAllAsTouched();
      return;
    }

    this.submitting = true;
    const loading = await this.loadingCtrl.create({
      message: 'Creating account...'
    });
    await loading.present();

    const { name, email, password } = this.form.value;

    this.auth.register({ name, email, password }).subscribe({
      next: async () => {
        await loading.dismiss();
        this.submitting = false;
        await this.router.navigateByUrl('/tabs', { replaceUrl: true });
      },
      error: async error => {
        await loading.dismiss();
        this.submitting = false;

        const message =
          error?.error?.message || 'Registration failed. Please check your information.';

        const toast = await this.toastCtrl.create({
          message,
          duration: 3000,
          color: 'danger'
        });
        await toast.present();
      }
    });
  }
}
