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
import { RouterLink } from '@angular/router';
import { Booking, BookingService } from '../../core/services/booking.service';

@Component({
  selector: 'app-booking-list',
  templateUrl: './booking-list.page.html',
  styleUrls: ['./booking-list.page.scss'],
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
    FormsModule,
    RouterLink
  ]
})
export class BookingListPage implements OnInit {
  bookings: Booking[] = [];
  loading = false;

  private bookingService = inject(BookingService);

  ngOnInit() {
    this.loadBookings();
  }

  loadBookings() {
    this.loading = true;

    this.bookingService.getBookings().subscribe({
      next: response => {
        this.bookings = response.data;
        this.loading = false;
      },
      error: () => {
        this.loading = false;
      }
    });
  }
}
