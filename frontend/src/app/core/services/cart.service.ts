import { Injectable, inject } from '@angular/core';
import { Observable } from 'rxjs';
import { ApiService } from './api.service';

export interface CartItem {
  id: number;
  product_id: number;
  quantity: number;
  unit_price: string;
  product?: {
    id: number;
    name: string;
    price: string;
    image_url?: string;
  };
}

export interface Cart {
  id: number;
  status: string;
  items: CartItem[];
}

@Injectable({ providedIn: 'root' })
export class CartService {
  private api = inject(ApiService);

  getCart(): Observable<Cart> {
    return this.api.get<Cart>('cart');
  }

  addItem(productId: number, quantity: number): Observable<Cart> {
    return this.api.post<Cart>('cart/items', { product_id: productId, quantity });
  }

  updateItem(id: number, quantity: number): Observable<Cart> {
    return this.api.put<Cart>(`cart/items/${id}`, { quantity });
  }

  removeItem(id: number): Observable<void> {
    return this.api.delete<void>(`cart/items/${id}`);
  }

  clearCart(): Observable<void> {
    return this.api.delete<void>('cart');
  }
}
