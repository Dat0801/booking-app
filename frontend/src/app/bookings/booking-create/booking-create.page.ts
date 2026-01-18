import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import {
  IonContent,
  IonHeader,
  IonTitle,
  IonToolbar,
  IonList,
  IonItem,
  IonLabel,
  IonInput,
  IonButton
} from '@ionic/angular/standalone';
import { ActivatedRoute, Router } from '@angular/router';
import { BookingService } from '../../core/services/booking.service';

@Component({
  selector: 'app-booking-create',
  templateUrl: './booking-create.page.html',
  styleUrls: ['./booking-create.page.scss'],
  standalone: true,
  imports: [
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonList,
    IonItem,
    IonLabel,
    IonInput,
    IonButton,
    CommonModule,
    FormsModule
  ]
})
export class BookingCreatePage implements OnInit {
  productId?: number;
  scheduledDate = '';
  startTime = '';
  endTime = '';
  notes = '';
  submitting = false;

  private bookingService = inject(BookingService);
  private route = inject(ActivatedRoute);
  private router = inject(Router);

  ngOnInit() {
    const productParam =
      this.route.snapshot.queryParamMap.get('product_id') ||
      this.route.snapshot.paramMap.get('product_id');

    if (productParam) {
      this.productId = Number(productParam);
    }
  }

  submit() {
    if (!this.productId || !this.scheduledDate || !this.startTime) {
      return;
    }

    this.submitting = true;

    this.bookingService
      .createBooking({
        product_id: this.productId,
        scheduled_date: this.scheduledDate,
        start_time: this.startTime,
        end_time: this.endTime || null,
        notes: this.notes || undefined
      })
      .subscribe({
        next: booking => {
          this.submitting = false;
          this.router.navigate(['/booking-detail'], { queryParams: { id: booking.id } });
        },
        error: () => {
          this.submitting = false;
        }
      });
  }
}
