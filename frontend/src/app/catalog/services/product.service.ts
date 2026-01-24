import { Injectable, inject } from '@angular/core';
import { ApiService } from '../../core/services/api.service';
import { Observable } from 'rxjs';

export interface Amenity {
  id: number;
  name: string;
  icon: string;
}

export interface Review {
  id: number;
  user_name: string;
  user_avatar?: string;
  rating: number;
  comment: string;
  date: string;
}

export interface Product {
  id: number;
  name: string;
  type: 'product' | 'service' | 'property';
  price: string;
  image_url?: string;
  description?: string;
  rating?: number;
  review_count?: number;
  location?: string;
  amenities?: Amenity[];
  reviews?: Review[];
  gallery?: string[];
}

export interface Category {
  id: number;
  name: string;
}

@Injectable({ providedIn: 'root' })
export class ProductService {
  private api = inject(ApiService);

  getProducts(params?: any): Observable<{ data: Product[] }> {
    return this.api.get<{ data: Product[] }>('products', params);
  }

  getProduct(id: number): Observable<Product> {
    return this.api.get<Product>(`products/${id}`);
  }

  getCategories(): Observable<{ data: Category[] }> {
    return this.api.get<{ data: Category[] }>('categories');
  }
}
