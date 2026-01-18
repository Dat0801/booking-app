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
  IonBadge
} from '@ionic/angular/standalone';
import { ActivatedRoute } from '@angular/router';
import { Booking, BookingService } from '../../core/services/booking.service';

@Component({
  selector: 'app-booking-detail',
  templateUrl: './booking-detail.page.html',
  styleUrls: ['./booking-detail.page.scss'],
  standalone: true,
  imports: [
    IonContent,
    IonHeader,
    IonTitle,
    IonToolbar,
    IonList,
    IonItem,
    IonLabel,
    IonBadge,
    CommonModule,
    FormsModule
  ]
})
export class BookingDetailPage implements OnInit {
  booking?: Booking;
  loading = false;

  private bookingService = inject(BookingService);
  private route = inject(ActivatedRoute);

  ngOnInit() {
    const idParam =
      this.route.snapshot.queryParamMap.get('id') || this.route.snapshot.paramMap.get('id');

    if (idParam) {
      const id = Number(idParam);
      this.loadBooking(id);
    }
  }

  loadBooking(id: number) {
    this.loading = true;

    this.bookingService.getBooking(id).subscribe({
      next: response => {
        this.booking = response;
        this.loading = false;
      },
      error: () => {
        this.loading = false;
      }
    });
  }
}
