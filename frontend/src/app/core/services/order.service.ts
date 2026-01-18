import { Injectable, inject } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';

export interface OrderItem {
  id: number;
  product_id: number;
  name: string;
  type: string;
  quantity: number;
  unit_price: string;
  subtotal: string;
}

export interface Order {
  id: number;
  order_number: string;
  status: string;
  total_amount: string;
  currency: string;
  payment_status: string;
  placed_at: string | null;
  items: OrderItem[];
}

interface PaginatedOrders {
  data: Order[];
}

@Injectable({ providedIn: 'root' })
export class OrderService {
  private api = inject(ApiService);

  getOrders(params?: any): Observable<PaginatedOrders> {
    return this.api.get<PaginatedOrders>('orders', params);
  }

  getOrder(id: number): Observable<Order> {
    return this.api.get<Order>(`orders/${id}`);
  }

  createOrder(cartId: number, paymentMethod?: string, notes?: string): Observable<Order> {
    return this.api.post<Order>('orders', {
      cart_id: cartId,
      payment_method: paymentMethod,
      notes,
    });
  }

  cancelOrder(id: number): Observable<Order> {
    return this.api.post<Order>(`orders/${id}/cancel`, {});
  }
}
