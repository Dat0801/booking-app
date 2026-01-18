import { Injectable, inject } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';

export interface Booking {
  id: number;
  booking_number: string;
  status: string;
  scheduled_date: string;
  start_time: string;
  end_time?: string | null;
  total_amount: string;
  payment_status: string;
  product?: {
    id: number;
    name: string;
    price: string;
  };
}

interface PaginatedBookings {
  data: Booking[];
}

@Injectable({ providedIn: 'root' })
export class BookingService {
  private api = inject(ApiService);

  getBookings(params?: any): Observable<PaginatedBookings> {
    return this.api.get<PaginatedBookings>('bookings', params);
  }

  getBooking(id: number): Observable<Booking> {
    return this.api.get<Booking>(`bookings/${id}`);
  }

  createBooking(input: {
    product_id: number;
    scheduled_date: string;
    start_time: string;
    end_time?: string | null;
    notes?: string;
  }): Observable<Booking> {
    return this.api.post<Booking>('bookings', input);
  }

  cancelBooking(id: number): Observable<Booking> {
    return this.api.post<Booking>(`bookings/${id}/cancel`, {});
  }
}
