import { Component, OnInit, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import {
  IonContent,
  IonHeader,
  IonTitle,
  IonToolbar,
  IonSpinner,
  IonIcon
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
    IonSpinner,
    IonIcon,
    CommonModule,
    FormsModule,
    RouterLink
  ]
})
export class BookingListPage implements OnInit {
  bookings: Booking[] = [];
  filteredBookings: Booking[] = [];
  loading = false;

  // Search and filter fields
  searchQuery = '';
  selectedStatus = '';
  selectedPaymentStatus = '';
  filterUserId = '';
  filterProductId = '';
  filterFromDate = '';
  filterToDate = '';
  includeDeleted = false;

  private bookingService = inject(BookingService);

  ngOnInit() {
    this.loadBookings();
  }

  loadBookings() {
    this.loading = true;

    this.bookingService.getBookings().subscribe({
      next: response => {
        this.bookings = response.data;
        this.applyFilters();
        this.loading = false;
      },
      error: () => {
        this.loading = false;
      }
    });
  }

  onSearch() {
    this.applyFilters();
  }

  onFilterChange() {
    this.applyFilters();
  }

  applyFilters() {
    let filtered = this.bookings;

    // Filter by search query
    if (this.searchQuery.trim()) {
      const query = this.searchQuery.toLowerCase();
      filtered = filtered.filter(booking => {
        return (
          booking.booking_number?.toLowerCase().includes(query) ||
          booking.guest_name?.toLowerCase().includes(query) ||
          booking.guest_email?.toLowerCase().includes(query)
        );
      });
    }

    // Filter by status
    if (this.selectedStatus) {
      filtered = filtered.filter(booking => {
        return booking.status?.toLowerCase() === this.selectedStatus.toLowerCase();
      });
    }

    // Filter by payment status
    if (this.selectedPaymentStatus) {
      filtered = filtered.filter(booking => {
        return booking.payment_status?.toLowerCase() === this.selectedPaymentStatus.toLowerCase();
      });
    }

    // Filter by User ID
    if (this.filterUserId.trim()) {
      filtered = filtered.filter(booking => {
        return booking.user_id?.toString().includes(this.filterUserId);
      });
    }

    // Filter by Product ID
    if (this.filterProductId.trim()) {
      filtered = filtered.filter(booking => {
        return booking.product_id?.toString().includes(this.filterProductId);
      });
    }

    // Filter by date range
    if (this.filterFromDate) {
      const fromDate = new Date(this.filterFromDate);
      filtered = filtered.filter(booking => {
        const bookingDate = new Date(booking.scheduled_date);
        return bookingDate >= fromDate;
      });
    }

    if (this.filterToDate) {
      const toDate = new Date(this.filterToDate);
      filtered = filtered.filter(booking => {
        const bookingDate = new Date(booking.scheduled_date);
        return bookingDate <= toDate;
      });
    }

    this.filteredBookings = filtered;
  }
}
